<?php

if (!isset($_SESSION['id'])):
    header("Location: ../../auth/login.php");
    exit;
elseif ($_SESSION['role'] !== "Admin"):
    header("Location: ../../client/standard.php");
    exit;
endif;   

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $partylist = $_POST['partylist'];
    $votecount = $_POST['votecount']; 

    require_once '../function/Model.php';
    $insertCandidate = new Model();
    $insertCandidate->setDatabaseTable('candidates');
    $prompting = $insertCandidate->insert($name, $position, $partylist, $votecount);
}

if (isset($_POST['search'])) {
    $searchField = $_POST['name'];

    require_once '../function/Model.php';
    $searchCandidate = new Model();
    $searchCandidate->setDatabaseTable('candidates');
    $dataCandidate = $searchCandidate->search($searchField);
}

if (isset($_POST['edit'])) {
    $editID = $_POST['id']; 
    $editName = $_POST['name']; 
    $editPosition = $_POST['position']; 
    $editPartylist = $_POST['partylist']; 

    echo $editID . $editName . $editPosition . $editPartylist;
}

if (isset($_POST['update'])) {
    require_once '../function/Model.php';
    $updateCandidate = new Model();
    $updateCandidate->setDatabaseTable('candidates');
    $updateCondition = $updateCandidate->candidateEdit($_POST['id'], $_POST['name'], $_POST['position'], $_POST['partylist']);

    $editID = $_POST['id']; 
    $editName = $_POST['name']; 
    $editPosition = $_POST['position']; 
    $editPartylist = $_POST['partylist']; 
}

if (isset($_POST['delete'])) {
    $deleteID = $_POST['id'];

    require_once '../function/Model.php';
    $deleteCandidate = new Model();
    $deleteCandidate->setDatabaseTable('candidates');
    $deleteCandidate->candidateDelete($deleteID);
}

if (isset($_POST['close'])) {
    require_once '../function/Model.php';
    unset($editID, $editName, $editPosition, $editPartylist);
}

require_once '../function/Model.php';
$showCandidates = new Model();
$showCandidates->setDatabaseTable('candidates');
$data = $showCandidates->index();

?>

<div class="container">
    <header class="dashboard-header">
        <h1>Dashboard</h1>
    </header>    

    <div class="dashboard-container">

        <!-- ADD CANDIDATES -->
        <form action="adminPanel.php" method="POST" class="add-candidate">
            <!-- NAME CONTAINER -->
            <div class="input-container">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : ''?>">
            </div>

            <!-- POSITION CONTAINER -->
            <div class="input-container">
                <label for="position">Position</label>
                <input type="text" name="position" id="position" value="<?php echo isset($position) ? $position : ''?>">
            </div>

            <!-- PARTY LIST CONTAINER -->
            <div class="input-container">
                <label for="partylist">Party List</label>
                <input type="text" name="partylist" id="partylist" value="<?php echo isset($partylist) ? $partylist : ''?>">
            </div>

            <!-- VOTE COUNT (HIDDEN) -->
            <input type="hidden" name="votecount" value="<?php echo isset($votecount) ? $votecount : 0 ?>">

            <!-- BUTTON CONTAINER -->
            <div class="button-container">
                <button type="submit" name="search"><i class="fas fa-save"></i> Search</button>
                <button type="submit" name="save"><i class="fas fa-save"></i> Create</button>
            </div>      
        </form>

        <!-- MESSAGE DIALOG -->
        <?php if (isset($prompting)): ?>
            <div class="alert-form">
                <p><?php echo $prompting; ?></p>
            </div>
        <?php endif; ?>

        <hr class="seperator-line">

        <!-- LIST OF CANDIDATES BASED ON SEARCH -->

        <?php if (isset($searchField)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Party List</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataCandidate as $candidatesSearch): ?>
                            <tr>
                                <td><?php echo $candidatesSearch['Name']; ?></td>
                                <td><?php echo $candidatesSearch['Position']; ?></td>
                                <td><?php echo $candidatesSearch['PartyList']; ?></td>
                                <td>
                                    <form action="adminPanel.php" method="POST">
                                        <input type="hidden" value="<?php echo $candidatesSearch['ID']; ?>" name="id">
                                        <input type="hidden" value="<?php echo $candidatesSearch['Name']; ?>" name="name">
                                        <input type="hidden" value="<?php echo $candidatesSearch['Position']; ?>" name="position">
                                        <input type="hidden" value="<?php echo $candidatesSearch['PartyList']; ?>" name="partylist">
                                        <button type="submit" name="edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this candidate?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Party List</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $candidatesSearch): ?>
                            <tr>
                                <td><?php echo $candidatesSearch['Name']; ?></td>
                                <td><?php echo $candidatesSearch['Position']; ?></td>
                                <td><?php echo $candidatesSearch['PartyList']; ?></td>
                                <td>
                                    <form action="adminPanel.php" method="POST">
                                        <input type="hidden" value="<?php echo $candidatesSearch['ID']; ?>" name="id">
                                        <input type="hidden" value="<?php echo $candidatesSearch['Name']; ?>" name="name">
                                        <input type="hidden" value="<?php echo $candidatesSearch['Position']; ?>" name="position">
                                        <input type="hidden" value="<?php echo $candidatesSearch['PartyList']; ?>" name="partylist">
                                        <button type="submit" name="edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this candidate?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- EDIT FORM -->
        <?php if (isset($editName)): ?>
            <div class="overlay"></div>
            <div class="edit-container">                
                <form action="adminPanel.php" method="POST">
                    <button type="submit" name="close">X</button>
                    <input type="text" value="<?php echo $editID; ?>" name="id">
                    <input type="text" value="<?php echo $editName; ?>" name="name">
                    <input type="text" value="<?php echo $editPosition; ?>" name="position">
                    <input type="text" value="<?php echo $editPartylist; ?>" name="partylist">
                    <button type="submit" name="update">Update</button>
                </form>

                <?php if (isset($updateCondition)): ?>
                    <p><?php echo $updateCondition; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>