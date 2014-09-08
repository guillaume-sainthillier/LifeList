var lifeListApp = angular.module('lifeListApp');
lifeListApp.service('RouteHandler', function()
{
    var service = {
        getRoute: function(name, params) {
            return Routing.generate(name, params || {}, true);
        },
        getResourceRoute: function(name, params, suffixe) {
            return service.getRoute(name, params) + "/:" + (suffixe || 'id').replace("//", "/");
        }
    };
    return service;
});
lifeListApp.service('Todo', function($resource, RouteHandler)
{
    var service = {
        resource: $resource(RouteHandler.getResourceRoute("api_todos"), {id: '@id'}),
        create: function(list, todo) {
            var params = angular.extend(service.tokenise(todo), {listId: list.id});
            var deferred = service.resource.save(null, params);
            deferred.$promise.then(function(data) {
                todo.id = data.id;
            }).catch(function(payload) {
                list.alerts.handle(payload.data);
            });
            return deferred;
        },
        edit: function(list, todo)
        {
            var deferred = service.resource.save({id: todo.id}, service.tokenise(todo));
            list.alerts && deferred.$promise.catch(function(payload) {
                list.alerts.handle(payload.data);
            });
            return deferred;
        },
        delete: function(todo)
        {
            var deferred = service.resource.delete({id: todo.id});
            deferred.$promise.catch(function(payload) {
                todo.alerts.handle(payload.data);
            });
            return deferred;
        },
        tokenise: function(todo) {
            return {name: todo.name, tags: todo.tags, completed: todo.completed};
        }
    };
    return service;
});
lifeListApp.service('Liste', function($resource, RouteHandler, AlertHandler)
{
    var service = {
        lists: [],
        resource: $resource(RouteHandler.getResourceRoute("api_lists"), {id: '@id'}),
        createAlerts: function(item)
        {
            item.alerts = item.alerts || AlertHandler.create();
        },
        fetch: function() {
            service.lists = service.resource.query();
            return service.lists;
        },
        create: function(params) {
            var liste = new service.resource(params); //new Liste(params)

            return liste.$save().then(function()
            {
                service.lists.push(liste);
            });
        },
        tokenise: function(list) {
            return {
                name: list.name
            };
        },
        save: function(list) {
            service.createAlerts(list);
            list.alerts.reset();

            var deferred = service.resource.save({id: list.id}, service.tokenise(list));
            deferred.$promise.catch(function(payload)
            {
                list.alerts.handle(payload.data);
            });

            return deferred;
        },
        delete: function(list) {
            var index = service.lists.indexOf(list);
            if (index === -1)
            {
                throw "La liste demandée pour suppression est introuvable";
            }
            service.createAlerts(list);
            list.alerts.reset();

            var deferred = service.resource.delete({id: list.id});
            deferred.$promise.then(function()
            {
                service.lists.splice(index, 1);
            }).catch(function(payload)
            {
                list.alerts.handle(payload.data);
            });

            return deferred;
        }
    };
    return service;
    return $resource(RouteHandler.getResourceRoute("api_lists"), {id: '@id'});
});
lifeListApp.service('AlertHandler', [function() {
        var service = {
            create: function() {
                var subservice = {
                    alerts: [],
                    add: function(type, msg) {
                        subservice.alerts.push({type: type, msg: msg});
                    },
                    reset: function() {
                        subservice.alerts = [];
                    },
                    close: function(index) {
                        subservice.alerts.splice(index, 1);
                    },
                    handle: function(data) {
                        var errors = [];
                        angular.forEach(data, function(sferrors)
                        {
                            if (sferrors.message)
                            {
                                errors.push(sferrors.message);
                            }
                        });
                        if (data.errors) //Formulaire Symfony2
                        {
                            if (data.errors.errors) //Global Errors
                            {
                                errors = angular.extend(errors, data.errors.errors);
                            }

                            errors = subservice.seekNodes(errors, data.errors.children);
                        }

                        errors.forEach(function(error)
                        {
                            subservice.add("danger", error);
                        });
                    },
                    seekNodes: function(accu, nodes) {
                        angular.forEach(nodes, function(node)
                        {
                            angular.forEach(node.errors, function(error)
                            {
                                if (accu.indexOf(error) === -1) //Si non présent, on l'ajoute
                                {
                                    accu.push(error);
                                }
                            });
                            accu = subservice.seekNodes(accu, node.children);
                        });
                        return accu;
                    }
                };
                return subservice;
            }
        };
        return service;
    }]);