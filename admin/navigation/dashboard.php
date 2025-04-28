<?php

if (!isset($_SESSION['id'])):
    header("Location: ../../auth/login.php");
    exit;
elseif ($_SESSION['role'] !== "Admin"):
    header("Location: ../../client/standard.php");
    exit;
endif;   

if (isset($_POST['addCandidate'])) {
    $name = '';
    $position = '';
    $partylist = '';
    $votecount = '';
}

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $partylist = $_POST['partylist'];
    $votecount = $_POST['votecount']; 

    require_once '../function/Model.php';
    $insertCandidate = new Model();
    $insertCandidate->setDatabaseTable('candidates');
    $prompting = $insertCandidate->insertCandidate($name, $position, $partylist, $votecount);
}

if (isset($_POST['search'])) {
    $searchField = $_POST['candidateSearchStatus'];

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
}

if (isset($_POST['update'])) {
    $editID = $_POST['id']; 
    $editName = $_POST['name']; 
    $editPosition = $_POST['position']; 
    $editPartylist = $_POST['partylist']; 
    
    require_once '../function/Model.php';
    $updateCandidate = new Model();
    $updateCandidate->setDatabaseTable('candidates');
    $updateCondition = $updateCandidate->candidateEdit($_POST['id'], $_POST['name'], $_POST['position'], $_POST['partylist']);

    $candidateID = new Model();
    $candidateID->setDatabaseTable('candidates');
    $dataCandidateID = $candidateID->candidateID($editID);

    foreach ($dataCandidateID as $candidacy) {
        $editName = $candidacy['Name']; 
        $editPosition = $candidacy['Position']; 
        $editPartylist = $candidacy['PartyList'];
    }

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

if (isset($_POST['refresh'])) {
    require_once '../function/Model.php';

    $refreshTable = true;
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

        <!-- HEADER CANDIDATES CONTAINER -->
        <form action="adminPanel.php" method="POST" class="candidates-container">
            <h2>List of Candidates</h1>

            <input type="hidden" name="dashboard" value="1">

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
                <button type="submit" name="addCandidate">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </form>

        <hr class="seperator-line">

        <!-- HEADER CANDIDATES BODY -->
        <?php if (isset($searchField) && $searchField != ''): ?>
            <div class="table-wrapper" id="candidateTable">
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
                        <?php if (isset($dataCandidate) && count($dataCandidate) > 0): ?>
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
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No candidates found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif(!isset($searchField)): ?>
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
        <?php elseif(isset($refreshTable) && $refreshTable == true): ?>
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

        <!-- ADD FORM -->
        <?php if (isset($name)): ?>
            <div class="overlay"></div>
            <div class="add-container">
                <form action="adminPanel.php" method="POST" class="add-candidate">
                    <input type="hidden" name="dashboard" value="1">
                    
                    <div class="close-container">
                        <h2>Add Candidate</h2>

                        <button type="submit" name="close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    
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

                    <!-- MESSAGE DIALOG -->
                    <?php if (isset($prompting)): ?>
                        <div class="alert-form">
                            <?php if ($prompting == 'Successfully Inserted'): ?>
                                <p style="color: green"><?php echo $prompting; ?></p>
                            <?php else: ?>
                                <p><?php echo $prompting; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>

                    <!-- BUTTON CONTAINER -->
                    <button type="submit" name="save" class="save">
                        <i class="fas fa-save"></i> Create
                    </button>      
                </form>
            </div>
        <?php endif; ?>

        <!-- EDIT FORM -->
        <?php if (isset($editName)): ?>
            <div class="overlay"></div>
            <div class="edit-container">                
                <form action="adminPanel.php" method="POST" class="edit-candidate">
                    <input type="hidden" name="dashboard" value="1">

                    <div class="close-container">
                        <h2>Edit Candidate</h2>

                        <button type="submit" name="close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <input type="hidden" value="<?php echo $editID; ?>" name="id">

                    <div class="input-container">
                        <label for="name">Name</label>
                        <input type="text" value="<?php echo $editName; ?>" name="name">
                    </div>

                    <div class="input-container">
                        <label for="position">Position</label>
                        <input type="text" value="<?php echo $editPosition; ?>" name="position">
                    </div>

                    <div class="input-container">
                        <label for="partylist">Party List</label>
                        <input type="text" value="<?php echo $editPartylist; ?>" name="partylist">
                    </div>
                    
                    <?php if (isset($updateCondition)): ?>
                        <?php if ($updateCondition == 'Successfully Updated!'): ?>
                            <p style="color: green"><?php echo $updateCondition ?></p>
                        <?php else: ?>
                            <p><?php echo $updateCondition ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>

                    <button type="submit" name="update" class="edit">Update</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.dashboard-container table tbody tr');

        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>