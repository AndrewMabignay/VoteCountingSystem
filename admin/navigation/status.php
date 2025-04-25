<?php
    if (!isset($_SESSION['id'])):
        header("Location: ../../auth/login.php");
        exit;
    elseif ($_SESSION['role'] !== "Admin"):
        header("Location: ../../client/standard.php");
        exit;
    endif;   

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
        <form action="" class="status-search-container">
            <h2>List of Candidates Status</h2>
            <div class="input-container">
                <input type="text" name="candidateSearchStatus">
                <label for="">
                    <i class="fas fa-search"></i>
                </label>
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
                    <?php foreach($data as $candidates): ?>
                        <tr>
                            <td><?php echo $candidates['Name'] ?></td>
                            <td><?php echo $candidates['Position'] ?></td>
                            <td><?php echo $candidates['PartyList'] ?></td>
                            <td><?php echo $candidates['VoteCount'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> 
    </div>
</div>