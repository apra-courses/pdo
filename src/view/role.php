<h3><?= $this->mode === 'insert' ? 'Nuovo ' : 'Modifica ' ?>Ruolo</h3>

<form method="POST" action="?action=confirmRole">
    <div id="entity_fields">
        <div>
            <span>Description</span>
            <input type="text" size="60"  id="description" name="description" autofocus value="<?= $this->role ? $this->role->getDescription() : '' ?>"                  
        </div>

        <div class="hidden_fields">
            <?php if ($this->role) : ?>
                <input type="hidden" id="id" name="id" value="<?= $this->role->getId() ?>">
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