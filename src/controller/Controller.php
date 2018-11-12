<?php

class Controller {

    const MODE_INSERT = 'insert';
    const MODE_UPDATE = 'update';

    private $userRepository;
    private $roleRepository;

    //inizializza i repositoy di base 
    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    public function index() {
        $this->renderAdmin();
    }

    private function renderAdmin($action = null, $mode = null, $users = null, $user = null, $roles = null, $role = null) {
        $view = new Template();
        $view->title = "Pdo User example";
        if ($mode !== null) {
            $view->mode = $mode;
        }
        if ($action !== null) {
            $view->action = $action;
        }
        if ($users !== null) {
            $view->users = $users;
        }
        if ($user !== null) {
            $view->user = $user;
        }

        if ($roles !== null) {
            $view->roles = $roles;
        }
        if ($role !== null) {
            $view->role = $role;
        }

        echo $view->render('admin.php');
    }

    public function newUser() {
        $this->renderAdmin('newUser', self::MODE_INSERT);
    }

    public function editUser() {
        $id = $_GET['id'];
        $user = $this->userRepository->findByID($id);
        $this->renderAdmin('newUser', self::MODE_UPDATE, null, $user);
    }

    public function confirmUser() {
        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setFirstname($_POST['firstname']);
        $user->setSurname($_POST['surname']);
        $user->setAge($_POST['age']);
        $user->setRegistrationDate($_POST['registrationDate']);

        if (!empty($_POST['role1'])) {
            $role1 = $this->roleRepository->findByDescription($_POST['role1']);
            $user->addRole($role1);
        }

        if (!empty($_POST['role2'])) {
            $role2 = $this->roleRepository->findByDescription($_POST['role2']);
            $user->addRole($role2);
        }


        $validationStatus = $this->validateUser($user);
        if ($validationStatus['EXITCODE'] !== 0) {
            $title = 'Dati non validi';
            $message = $validationStatus['ERRORMSG'];
        } else {
            switch ($_POST['mode']) {
                case self::MODE_INSERT:
                    if ($this->userRepository->insert($user)) {
                        $title = 'Utente inserito con successo';
                        $message = "utente: {$user->getUsername()}";
                    } else {
                        $title = 'Errore inserimento utente';
                        $message = "Si è verificato un erorre durante inserimento utente. Controllare i log.";
                    }
                    break;
                case self::MODE_UPDATE:
                    $user->setId($_POST['id']);
                    if ($this->userRepository->update($user)) {
                        $title = 'Utente modificato con successo';
                        $message = "Utente: {$user->toString()}";
                    } else {
                        $title = 'Errore inserimento utente';
                        $message = "Si è verificato un erorre durante modifica utente. Controllare i log.";
                    }
                    break;
            }
        }
        $actions = array(
            array(
                'link' => '?action=viewUsers',
                'title' => 'Vai a elenco utenti'
            )
        );
        $this->renderNotifyContainer($title, $message, $actions);
    }

    public function deleteUser() {
        $id = $_GET['id'];

        if ($this->userRepository->delete($id)) {
            $title = 'User eleminato con successo';
            $message = "ID User: $id";
        } else {
            $title = 'Errore cancellazione utente';
            $message = "Si è verificato un erorre durante cancellazione utente. Controllare i log.";
        }
        $actions = array(
            array(
                'link' => '?action=viewUsers',
                'title' => 'Vai a elenco user'
            )
        );
        $this->renderNotifyContainer($title, $message, $actions);
    }

    public function viewUsers() {
        $users = $this->userRepository->findAll();
        $this->renderAdmin('viewUsers', null, $users);
    }

    private function validateUser(User $user) {
        $toReturn = array(
            'EXITCODE' => 0,
            'ERRORMSG' => ''
        );

        if (strlen($user->getUsername()) === 0) {
            $toReturn['EXITCODE'] = 1;
            $toReturn['ERRORMSG'] = 'username non valorizzato';
            return $toReturn;
        }

        if (strlen($user->getFirstname()) === 0) {
            $toReturn['EXITCODE'] = 1;
            if (strlen($toReturn['ERRORMSG']) > 0) {
                $toReturn['ERRORMSG'] .= '<br>';
            }
            $toReturn['ERRORMSG'] .= 'Nome non valorizzato';
            return $toReturn;
        }

        if ($user->getRegistrationDate()) {
            $d = DateTime::createFromFormat("Y-m-d", $user->getRegistrationDate());
            if (!$d) {
                $toReturn['EXITCODE'] = 1;
                if (strlen($toReturn['ERRORMSG']) > 0) {
                    $toReturn['ERRORMSG'] .= '<br>';
                }
                $toReturn['ERRORMSG'] .= 'Data non valida';
                return $toReturn;
            }
        }

        if (strlen($user->getSurname()) === 0) {
            $toReturn['EXITCODE'] = 1;
            if (strlen($toReturn['ERRORMSG']) > 0) {
                $toReturn['ERRORMSG'] .= '<br>';
            }
            $toReturn['ERRORMSG'] .= 'Cognome non valorizzato';
            return $toReturn;
        }

        if ($user->getAge() == null || is_numeric($user->getAge()) == false) {
            $toReturn['EXITCODE'] = 1;
            if (strlen($toReturn['ERRORMSG']) > 0) {
                $toReturn['ERRORMSG'] .= '<br>';
            }
            $toReturn['ERRORMSG'] .= 'Età non valorizzato o valore non consono';
            return $toReturn;
        }
        if ($_POST['mode'] == self::MODE_INSERT) {
            if ($user->getRoles() == null) {
                $toReturn['EXITCODE'] = 1;
                if (strlen($toReturn['ERRORMSG']) > 0) {
                    $toReturn['ERRORMSG'] .= '<br>';
                }
                $toReturn['ERRORMSG'] .= 'Ruoli non valorizzati';
                return $toReturn;
            }
        }


        return $toReturn;
    }

    public function deleteRole() {
        $id = $_GET['id'];
        if ($this->roleRepository->delete($id)) {
            $title = 'Role eleminato con successo';
            $message = "ID Role: $id";
        } else {
            $title = 'Errore cancellazione role';
            $message = "Si è verificato un erorre durante cancellazione del ruolo. Controllare i log.";
        }
        $actions = array(
            array(
                'link' => '?action=viewRoles',
                'title' => 'Vai a elenco roles'
            )
        );
        $this->renderNotifyContainer($title, $message, $actions);
    }

    public function newRole() {
        $this->renderAdmin('newRole', self::MODE_INSERT);
    }

    public function viewRoles() {
        $roles = $this->roleRepository->findAll();
        $this->renderAdmin('viewRoles', null, null, null, $roles);
    }

    public function editRole() {
        $id = $_GET['id'];
        $role = $this->roleRepository->findByID($id);
        $this->renderAdmin('newRole', self::MODE_UPDATE, null, null, null, $role);
    }

    public function confirmRole() {
        $role = new Role();
        $role->setDescription($_POST['description']);
        $validationStatus = $this->validateRole($role);
        if ($validationStatus['EXITCODE'] !== 0) {
            $title = 'Dati non validi';
            $message = $validationStatus['ERRORMSG'];
        } else {
            switch ($_POST['mode']) {
                case self::MODE_INSERT:
                    if ($this->roleRepository->insert($role)) {
                        $title = 'Ruolo inserito con successo';
                        $message = "Ruolo: {$role->getDescription()}";
                    } else {
                        $title = 'Errore inserimento ruolo';
                        $message = "Si è verificato un erorre durante inserimento ruolo. Controllare i log.";
                    }
                    break;
                case self::MODE_UPDATE:
                    $role->setId($_POST['id']);
                    if ($this->roleRepository->update($role)) {
                        $title = 'Ruolo modificato con successo';
                        $message = "Ruolo: {$role->toString()}";
                    } else {
                        $title = 'Errore inserimento ruolo';
                        $message = "Si è verificato un erorre durante modifica ruolo. Controllare i log.";
                    }
                    break;
            }
        }
        $actions = array(
            array(
                'link' => '?action=viewRoles',
                'title' => 'Vai a elenco ruoli'
            )
        );
        $this->renderNotifyContainer($title, $message, $actions);
    }

    private function validateRole(Role $role) {
        $toReturn = array(
            'EXITCODE' => 0,
            'ERRORMSG' => ''
        );

        if (strlen($role->getDescription()) === 0) {
            $toReturn['EXITCODE'] = 1;
            $toReturn['ERRORMSG'] = 'descrizione non valorizzato';
            return $toReturn;
        }
        return $toReturn;
    }

    private function renderNotifyContainer($title, $message, $actions) {
        $view = new Template();
        $view->title = $title;
        $view->message = $message;
        $view->actions = $actions;
        echo $view->render('notify.php');
    }

}
