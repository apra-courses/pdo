<h3>Elenco ruoli</h3>

<div>
    <table id="table-roles" style="width:80%">    
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Azioni</th>     
            </tr>        
        </thead>
        <tbody>
            <?php foreach ($this->roles as $role): ?>
                <tr>
                    <td><?= $role->getId() ?></td>
                    <td><?= $role->getDescription() ?></td>
                    <td>
                        <span><a href="?action=editRole&id=<?= $role->getId() ?>">Modifica</a></span> |
                        <span><a href="?action=deleteRole&id=<?= $role->getId() ?>">Elimina</a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
