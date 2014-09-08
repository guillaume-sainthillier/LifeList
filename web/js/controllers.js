var lifeListApp = angular.module('lifeListApp');

lifeListApp.controller('MainCtrl', function($scope, AlertHandler) {
    $scope.alerts = AlertHandler.create();
    $scope.currentArea = "Bienvenue !";
});
lifeListApp.controller('AccueilCtrl', function($scope) {

});

lifeListApp.controller('ModalDeleteListCtrl', function($rootScope, $scope, $modal) {

    $scope.open = function(list, size) {
        $scope.list = list;
        $scope.modal = $modal.open({
            templateUrl: 'modalConfirmDeleteList.html',
            controller: 'ModalDeleteListCtrl',
            size: size,
            scope: $scope
        });
    };

    $scope.ok = function(list)
    {
        $rootScope.$broadcast('liste.delete', list);
        $scope.cancel();
    };

    $scope.cancel = function()
    {
        $scope.modal.dismiss();
    };
});

lifeListApp.controller('ListCtrl', function($scope, filterFilter, AlertHandler, Liste, Todo) {
    $scope.lists = Liste.fetch();

    $scope.alerts = AlertHandler.create();

    $scope.$on('liste.delete', function($event, list)
    {
        $scope.deleteList(list);
    });

    $scope.status = [
        {name: 'Toutes', state: null},
        {name: 'Restantes', state: false},
        {name: 'Terminées', state: true}
    ];

    $scope.isLoading = function(item)
    {
        if (item !== undefined)
        {
            if (typeof item.deferred !== "undefined")
            {
                return !item.deferred.$resolved;
            }
            if (typeof item.$resolved === "boolean")
            {
                return !item.$resolved;
            }
        }
        return false;
    };


    $scope.$watch('lists', function(lists)
    {
        if ($scope.isLoading(lists))
        {
            return;
        }

        angular.forEach(lists, function(list)
        {
            list.alerts = list.alerts || AlertHandler.create();
            list.remaining = filterFilter(list.todos, {completed: false}).length;
            list.news = filterFilter(list.todos, {notSaved: true}).length;
            list.checkall = !list.remaining;
        });

        if (lists.length === 0)
        {
            $scope.alerts.add("warning", "Aucune liste créée pour le moment ! Qu'attendez-vous ?");
        } else
        {
            $scope.alerts.reset();
        }
    }, true);

    $scope.checkAllTodo = function(list)
    {
        list.todos.forEach(function(todo)
        {
            todo.completed = !list.checkall;
            $scope.editTodo(list, todo);
        });
    };

    $scope.filterStatus = function(status, list)
    {
        list.statusFilter = list.statusFilter || {};

        //Status: null, false ou true
        list.default_status = status.state;
        //list.statusFilter = list.default_status;
        if (status.state === null && typeof list.statusFilter.completed !== "undefined") //Pas de filtre par completed
        {
            delete list.statusFilter.completed;
        } else
        {
            list.statusFilter.completed = status.state;
        }
    };

    /*
     * 
     * Lists
     */

    $scope.addList = function()
    {
        $scope.alerts.reset();
        $scope.newList = false;

        //Persistance
        Liste.create({name: $scope.newlistname}).then(function()
        {
            $scope.newlistname = '';
        }).catch(function(payload)
        {
            $scope.newList = true;
            $scope.alerts.handle(payload.data);
        });
    };

    $scope.editList = function(list)
    {
        list.deferred = Liste.save(list);
        list.deferred.$promise.then(function()
        {
            list.editing = false;
        });
    };

    $scope.deleteList = function(list)
    {
        Liste.delete(list);
    };

    /*
     * Todos
     * 
     */
    $scope.addTodo = function(list)
    {
        var notSavedTodos = filterFilter(list.todos, {notSaved: true});
        notSavedTodos.forEach(function(todo)
        {
            todo.editing = true;
        });

        if (!notSavedTodos.length)
        {
            list.todos.push({name: '', tags: [], completed: false, editing: true, notSaved: true});
        }
    };

    $scope.editTodo = function(list, todo)
    {
        if (todo.name)
        {
            todo.editing = false;
            list.alerts.reset();
            if (todo.id === undefined) // Ajout
            {
                todo.deferred = Todo.create(list, todo);
                todo.deferred.$promise.then(function()
                {
                    todo.notSaved = false;
                });
            } else //Edition
            {
                todo.deferred = Todo.edit(list, todo);
            }

            todo.deferred.$promise.catch(function() {
                todo.editing = true;
            });
        } else
        {
            list.alerts.add("danger", "Le nom de la tâche ne peut pas être vide");
        }
    };

    $scope.deleteTodo = function(list, todo) {
        var index = list.todos.indexOf(todo);
        if (index >= 0)
        {
            todo.deferred = Todo.delete(todo);
            todo.deferred.$promise.then(function()
            {
                list.todos.splice(index, 1);
            });
        }
    };
    /*
     * Tags
     */
    $scope.addTag = function(list, todo)
    {
        if (todo.tags.indexOf(todo.newtag) !== -1)
        {
            list.alerts.add("warning", "Le thème '" + todo.newtag + "' a déjà été ajouté !");
        } else
        {
            list.alerts.reset();
            todo.tags.push(todo.newtag);
            todo.newtag = '';
        }
    };

    $scope.deleteTag = function(todo, index)
    {
        todo.tags.splice(index, 1);
    };
});