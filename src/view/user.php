<h3><?= $this->mode === 'insert' ? 'Nuovo ' : 'Modifica ' ?>Utente</h3>

<form method="POST" action="?action=confirmUser">
    <div id="entity_fields">
        <div>
            <span>Username</span>
            <input type="text" size="60"  id="userName" name="username" autofocus value="<?= $this->user ? $this->user->getUsername() : '' ?>"                  
        </div>

        <div>
            <span>Firstname</span>
            <input type="text" id="firstname" size="60" name="firstname" value="<?= $this->user ? $this->user->getFirstname() : '' ?>">                    
        </div>

        <div>
            <span>Surname</span>
            <input type="text" id="surname" size="60" name="surname" value="<?= $this->user ? $this->user->getSurname() : '' ?>">                    
        </div>

        <div>
            <span>Age</span>
            <input type="text" id="age" name="age" size="20" autofocus value="<?= $this->user ? $this->user->getAge() : '' ?>">                    
        </div>

        <div>
            <span>RegistrationDate</span>
            <input type="text" id="registrationDate" name="registrationDate" size="30" autofocus value="<?= $this->user ? $this->user->getRegistrationDate() : '' ?>">                    
        </div>

        <?php if ($this->mode != 'update') : ?>
            <div>
                <span>Descritpion Role 1 </span>
                <input type="text" id="role1" name="role1" size="20" autofocus value="<?= $this->user ? $this->user->getRole1() : '' ?>">                    
            </div>

            <div>
                <span>Descritpion Role 2</span>
                <input type="text" id="role2" name="role2" size="20" autofocus value="<?= $this->user ? $this->user->getRole2() : '' ?>">                    
            </div>
        <?php endif; ?>   

        <div class="hidden_fields">
            <?php if ($this->user) : ?>
                <input type="hidden" id="id" name="id" value="<?= $this->user->getId() ?>">
            <?php endif; ?>
            <input type="hidden" id="mode" name="mode" value="<?= $this->mode ?>">
        </div>
    </div>
    <div id="entity_buttons">
        <input type="submit" value="Conferma">
        <input type="reset" value="Annulla">
    </div>     

    <?php if (strlen($this->validationMessage) > 0): ?>
        <div class="error-messages">
            <?= $this->validationMessage ?>                
        </div>
    <?php endif; ?>              
</form>