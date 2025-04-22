<?php
    if (isset($_POST['userEdit'])) {
        $editUserID = $_POST['userId'];
        $editUserName = $_POST['userName'];
        $editUserAge = $_POST['userAge'];
        $editUserAddress = $_POST['userAddress'];
        $editUserPassword = $_POST['userPassword'];
        $editUserRole = $_POST['userRole'];
    }

    require_once '../function/Model.php';
    $users = new Model();
    $users->setDatabaseTable('accounts');
    $data = $users->index();
?>

<div class="card">
    <header class="status-header">
        <h1>List of Users</h1>
    </header>

    <div class="status-container">
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="<?php echo isset($editUserName) ? 'no-animation' : ''; ?>">
                    <?php foreach($data as $user): ?>
                        <tr>
                            <td><?php echo $user['Name'] ?></td>
                            <td><?php echo $user['Age'] ?></td>
                            <td><?php echo $user['Address'] ?></td>
                            <td><?php echo $user['Password'] ?></td>
                            <td><?php echo $user['Role'] ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="users" value="1">
                                
                                    <!-- ID -->
                                    <input type="hidden" value="<?php echo $user['ID']; ?>" name="userId">

                                    <!-- NAME -->
                                    <input type="hidden" value="<?php echo $user['Name']; ?>" name="userName">
                                    
                                    <!-- AGE -->
                                    <input type="hidden" value="<?php echo $user['Age']; ?>" name="userAge">
                                    
                                    <!-- ADDRESS -->
                                    <input type="hidden" value="<?php echo $user['Address']; ?>" name="userAddress">

                                    <!-- PASSWORD -->
                                    <input type="hidden" value="<?php echo $user['Password']; ?>" name="userPassword">

                                    <!-- ROLE -->
                                    <select name="userRole" id="role" style="display: none;">
                                        <option value="User" <?php echo ($user['Role'] == 'User') ? 'selected' : ''; ?>>User</option>
                                        <option value="Admin" <?php echo ($user['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    
                                    <button type="submit" name="userEdit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- EDIT FORM -->
        <?php if (isset($editUserName)): ?>
            <div class="overlay"></div>
            <div class="edit-container">                
                <form action="adminPanel.php" method="POST">
                    <input type="hidden" name="users" value="1">
                    <div class="header-form-container">
                        <h2>Edit Form</h2>  
                        <button type="submit" name="userClose">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <input type="hidden" value="<?php echo $editUserID; ?>" name="userId">
                    
                    <!-- NAME CONTAINER -->
                    <div class="input-container">
                        <label for="userName">Name</label>
                        <input type="text" value="<?php echo $editUserName; ?>" name="userName" id="userName">
                    </div>
                    
                    <!-- AGE CONTAINER -->
                    <div class="input-container">
                        <label for="userAge">Age</label>
                        <input type="text" value="<?php echo $editUserAge; ?>" name="userAge" id="userAge">
                    </div>

                    <!-- ADDRESS CONTAINER -->
                    <div class="input-container">
                        <label for="userAddress">Address</label>
                        <input type="text" value="<?php echo $editUserAddress; ?>" name="userAddress" id="userAddress">
                    </div>

                    <!-- PASSWORD CONTAINER -->
                    <div class="input-container">
                        <label for="userPassword">Password</label>
                        <input type="text" value="<?php echo $editUserPassword; ?>" name="userPassword" id="userPassword">
                    </div>

                    <!-- ROLE CONTAINER -->
                    <div class="input-container">
                        <label for="userRole">Role</label>
                        <select name="userRole" id="userRole">
                            <option value="User" <?php echo ($editUserRole == 'User') ? 'selected' : ''; ?>>User</option>
                            <option value="Admin" <?php echo ($editUserRole == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <button type="submit" name="userUpdate">Update User</button>
                </form>

                <?php if (isset($updateCondition)): ?>
                    <p><?php echo $updateCondition; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>