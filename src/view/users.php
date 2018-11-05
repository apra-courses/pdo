<h3>Elenco Utenti</h3>

<div>
    <table id="table-users" style="width:80%">    
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>FirstName</th>
                <th>Surname</th>
                <th>Age</th>  
                <th>RegistrationDate</th>  
                <th>Azioni</th>     
            </tr>        
        </thead>
        <tbody>
            <?php foreach ($this->users as $user): ?>
                <tr>
                    <td><?= $user->getId() ?></td>
                    <td><?= $user->getUsername() ?></td>
                    <td><?= $user->getFirstname() ?></td>
                    <td><?= $user->getSurname() ?></td>
                    <td><?= $user->getAge() ?></td>
                    <td><?= $user->getRegistrationDate() ?></td>
                    <td>
                        <span><a href="?action=editUser&id=<?= $user->getId() ?>">Modifica</a></span> |
                        <span><a href="?action=deleteUser&id=<?= $user->getId() ?>">Elimina</a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
