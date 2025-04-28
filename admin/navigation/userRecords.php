<?php
    if (!isset($_SESSION['id'])):
        header("Location: ../../auth/login.php");
        exit;
    elseif ($_SESSION['role'] !== "Admin"):
        header("Location: ../../client/standard.php");
        exit;
    endif;   

    if (isset($_POST['userEdit'])) {
        $editUserID = $_POST['userId'];
        $editUserName = $_POST['userName'];
        $editUserAge = $_POST['userAge'];
        $editUserAddress = $_POST['userAddress'];
        $editUserUsername = $_POST['userUsername'];
        $editUserPassword = $_POST['userPassword'];
        $editUserRole = $_POST['userRole'];
    }

    // ADD NEW CHANGES 
    if (isset($_POST['userCreate'])) {
        $addUser = true;
    }

    if (isset($_POST['newUserAdded'])) {
        $addUser = true;

        $addUserName = $_POST['userName'];
        $addUserAge = $_POST['userAge'];
        $addUserAddress = $_POST['userAddress'];
        $addUserUsername = $_POST['userUsername'];
        $addUserPassword = $_POST['userPassword'];
        $addUserRole = $_POST['userRole'];

        // echo $addUserName . $addUserAge . $addUserAddress . $addUserUsername . $addUserPassword . $addUserRole;

        require_once '../function/Model.php';
        $addUser = new Model();
        $addUser->setDatabaseTable('accounts'); 
        $addPrompting = $addUser->addUser($addUserName, $addUserAge, $addUserAddress, $addUserUsername, $addUserPassword, $addUserRole);   

        if ($addPrompting == 'Successfully Added') {
            unset($addUserName, $addUserAge, $addUserAddress, $addUserUsername, $addUserPassword, $addUserRole);
        }
    }

    if (isset($_POST['userUpdate'])) {
        $editUserID = $_POST['userId'];
        $editUserName = $_POST['userName'];
        $editUserAge = $_POST['userAge'];
        $editUserAddress = $_POST['userAddress'];
        $editUserUsername = $_POST['userUsername'];
        $editUserPassword = $_POST['userPassword'];
        $editUserRole = $_POST['userRole'];

        require_once '../function/Model.php';
        $editUser = new Model();
        $editUser->setDatabaseTable('accounts'); 
        $editPrompting = $editUser->editUser($editUserID, $editUserName, $editUserAge, $editUserAddress, $editUserUsername, $editUserPassword, $editUserRole);   
    }

    if (isset($_POST['userDelete'])) {
        $deleteUserId = $_POST['userId'];
    
        require_once '../function/Model.php';
        $deleteUser = new Model();
        $deleteUser->setDatabaseTable('accounts');
        $deleteUser->deleteUser($deleteUserId);
    }

    require_once '../function/Model.php';

    $users = new Model();
    $users->setDatabaseTable('accounts');
    $data = $users->showUser($_SESSION['id']);
?>

<div class="user-container">
    <header class="user-header">
        <h1>User Management</h1>
    </header>

    <div class="user-container-main">
        <form action="adminPanel.php?page=users" method="POST" class="voters-container">
            <h2>List of Users</h2>

            <input type="hidden" name="users" value="1">

            <div class="button-container">
                <!-- SEARCH CONTAINER -->
                <div class="search-container">
                    <input type="text" name="candidateSearchStatus">
                    <button type="submit" name="search">
                        <label for="">
                            <i class="fas fa-search"></i>
                        </label>
                    </button>
                </div>

                <!-- REFRESH CONTAINER -->
                <button type="submit" name="refresh">
                    <i class="fas fa-sync"></i>
                </button>

                <!-- ADD CANDIDATE CONTAINER -->
                <button type="submit" name="userCreate">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </form>

        <hr class="seperator-line">

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Username</th>
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
                            <td><?php echo $user['Username'] ?></td>
                            <td><?php echo substr(md5($user['Password']), 0, 8); ?></td>
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

                                    <!-- USERNAME -->
                                    <input type="hidden" value="<?php echo $user['Username']; ?>" name="userUsername">

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
                                    <button type="submit" name="userDelete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ADD FORM -->
        <?php if (isset($addUser) && $addUser == true): ?>
            <div class="overlay"></div>
            <div class="add-container">                
                <form action="adminPanel.php?page=users" method="POST" class="add-container-user">
                    <input type="hidden" name="users" value="1">
                    <div class="close-container">
                        <h2>Add User</h2>  
                        <button type="submit" name="userClose">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <!-- NAME CONTAINER -->
                    <div class="input-container">
                        <label for="userName">Name</label>
                        <input type="text" value="<?php echo isset($addUserName) ? $addUserName : '' ?>" name="userName" id="userName">
                    </div>
                    
                    <!-- AGE CONTAINER -->
                    <div class="input-container">
                        <label for="userAge">Age</label>
                        <input type="number" value="<?php echo isset($addUserAge) ? $addUserAge : '' ?>" name="userAge" id="userAge">
                    </div>

                    <!-- ADDRESS CONTAINER -->
                    <div class="input-container">
                        <label for="userAddress">Address</label>
                        <input type="text" value="<?php echo isset($addUserAddress) ? $addUserAddress : '' ?>" name="userAddress" id="userAddress">
                    </div>

                    <!-- USERNAME CONTAINER -->
                    <div class="input-container">
                        <label for="userUsername">Username</label>
                        <input type="text" value="<?php echo isset($addUserUsername) ? $addUserUsername : '' ?>" name="userUsername" id="userUsername">
                    </div>

                    <!-- PASSWORD CONTAINER -->
                    <div class="input-container">
                        <label for="userPassword">Password</label>
                        <input type="text" value="<?php echo isset($addUserPassword) ? $addUserPassword : '' ?>" name="userPassword" id="userPassword">
                    </div>

                    <!-- ROLE CONTAINER -->
                    <div class="input-container">
                        <label for="userRole">Role</label>
                        <select name="userRole" id="userRole">
                            <option value="User" <?php echo isset($addUserRole) && ($addUserRole == 'User') ? 'selected' : '' ?>>User</option>
                            <option value="Admin" <?php echo isset($addUserRole) && ($addUserRole == 'Admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <?php if (isset($addPrompting)): ?>
                        <div class="alert-form">
                            <?php if ($addPrompting == 'Successfully Added'): ?>
                                <p style="color: green"><?php echo $addPrompting; ?></p>
                            <?php else: ?>
                                <p><?php echo $addPrompting; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>

                    <button type="submit" name="newUserAdded" class="save">
                        <i class="fas fa-save"></i> Create
                    </button>
                </form>

                <?php if (isset($updateCondition)): ?>
                    <p><?php echo $updateCondition; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
            
        <!-- EDIT FORM -->
        <?php if (isset($editUserName)): ?>
            <div class="overlay"></div>
            <div class="edit-container">                
                <form action="adminPanel.php?page=users" method="POST" class="edit-user-container">
                    <input type="hidden" name="users" value="1">
                    <div class="close-container">
                        <h2>Edit User</h2>  
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
                    
                    <!-- USERNAME CONTAINER -->
                    <div class="input-container">
                        <label for="userUsername">Username</label>
                        <input type="text" value="<?php echo $editUserUsername; ?>" name="userUsername" id="userUsername">
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

                    <?php if (isset($editPrompting)): ?>
                        <div class="alert-form">
                            <?php if ($editPrompting == 'Successfully Updated!'): ?>
                                <p style="color: green"><?php echo $editPrompting; ?></p>
                            <?php else: ?>
                                <p><?php echo $editPrompting; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>

                    <button type="submit" name="userUpdate" class="edit">Update</button>
                </form>

                <?php if (isset($updateCondition)): ?>
                    <p><?php echo $updateCondition; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.user-container table tbody tr');

        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>