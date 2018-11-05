<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $this->title ?></title>
        <link rel="stylesheet" href="/corsophp-pdouser/public/css/style.css" type="text/css" />
    </head>
    <body>
        <!-- container -->
        <div id="container">

            <!-- header -->
            <div id="header">
                <br> 
                Home page base azioni corso pdo

            </div>

            <!-- sidebar -->
            <div id="sidebar">

                <div id="sidebar_admin_actions">
                    <span class="sidebar-title">Azioni</span>
                    <ul>
                        <li><a href="?action=newUser">Nuovo utente</a></li>
                        <li><a href="?action=viewUsers">Gestione utente</a></li>

                        <li><a href="?action=newRole">Nuovo ruolo</a></li>
                        <li><a href="?action=viewRoles">Gestione ruoli</a></li>
                    </ul>
                </div>

            </div>

            <!-- content -->
            <div id="content">       
                <?php if ($this->action === 'newUser' || $this->action === 'editUser'): ?>
                    <?php include(VIEW_DIR . '/user.php'); ?>
                <?php elseif ($this->action === 'viewUsers'): ?>
                    <?php include(VIEW_DIR . '/users.php'); ?>
                <?php elseif ($this->action === 'viewRoles'): ?>
                    <?php include(VIEW_DIR . '/roles.php'); ?>
                <?php elseif ($this->action === 'newRole' || $this->action === 'editRole'): ?>
                    <?php include(VIEW_DIR . '/role.php'); ?>
                <?php endif; ?>
            </div>

        </div>                        
    </body>
</html>
