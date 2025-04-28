<?php
    if (!isset($_SESSION['id'])):
        header("Location: ../../auth/login.php");
        exit;
    elseif ($_SESSION['role'] !== "Admin"):
        header("Location: ../../client/standard.php");
        exit;
    endif;   

    if (isset($_POST['searchCandidateStatus'])) {
        $searchField = $_POST['candidateSearchStatus'];
     
        require_once '../function/Model.php';
        $searchCandidateStatus = new Model();
        $searchCandidateStatus->setDatabaseTable('candidates');
        $dataStatus = $searchCandidateStatus->search($searchField);
    }

    if (isset($_POST['refresh'])) {
        require_once '../function/Model.php';
    
        $refreshTable = true;
    }

    require_once '../function/Model.php';

    $listCandidates = new Model();
    $listCandidates->setDatabaseTable('candidates');
    $data = $listCandidates->candidateList();
?>

<div class="card">
    <header class="status-header">
        <h1>Status</h1>
    </header>

    <div class="status-container">
        
        <!-- 1. START LIST OF CANDIDATES STATUS SEARCH CONTAINER -->
        <form action="" class="status-search-container" method="POST">
            <input type="hidden" name="status" value="1">

            <h2>List of Candidates Status</h2>
            <div class="input-container">
                <div class="search-container">
                    <input type="text" name="candidateSearchStatus">
                    <button type="submit" name="searchCandidateStatus">
                        <label for="">
                            <i class="fas fa-search"></i>
                        </label>
                    </button>
                </div>

                <!-- REFRESH CONTAINER -->
                <button type="submit" name="refresh">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
        </form>
        <!-- 1. END LIST OF CANDIDATES STATUS SEARCH CONTAINER -->

        <hr class="seperator-line">

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Party List</th>
                        <th>Votes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($searchField) && $searchField != '') : ?>
                        <?php if (isset($dataStatus) && count($dataStatus) > 0): ?>
                            <?php foreach($dataStatus as $candidatesStatus): ?>
                                <tr>
                                    <td><?php echo $candidatesStatus['Name'] ?></td>
                                    <td><?php echo $candidatesStatus['Position'] ?></td>
                                    <td><?php echo $candidatesStatus['PartyList'] ?></td>
                                    <td><?php echo $candidatesStatus['VoteCount'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No candidates found.</td>
                            </tr>
                        <?php endif; ?>
                    <?php elseif (isset($searchCandidateStatus) && $searchField == ''): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No candidates found.</td>
                        </tr>
                    <?php elseif(isset($refreshTable) && $refreshTable == true): ?>
                        <?php foreach($data as $candidatesStatus): ?>
                            <tr>
                                <td><?php echo $candidatesStatus['Name'] ?></td>
                                <td><?php echo $candidatesStatus['Position'] ?></td>
                                <td><?php echo $candidatesStatus['PartyList'] ?></td>
                                <td><?php echo $candidatesStatus['VoteCount'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach($data as $candidates): ?>
                            <tr>
                                <td><?php echo $candidates['Name'] ?></td>
                                <td><?php echo $candidates['Position'] ?></td>
                                <td><?php echo $candidates['PartyList'] ?></td>
                                <td><?php echo $candidates['VoteCount'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>              
                </tbody>
            </table>
        </div> 
    </div>
</div>