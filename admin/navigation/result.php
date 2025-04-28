<?php
    if (!isset($_SESSION['id'])):
        header("Location: ../../auth/login.php");
        exit;
    elseif ($_SESSION['role'] !== "Admin"):
        header("Location: ../../client/standard.php");
        exit;
    endif;   

    if (isset($_POST['searchCandidateResult'])) {
        $searchField = $_POST['candidateSearchStatus'];
     
        require_once '../function/Model.php';
        $searchCandidateStatus = new Model();
        $searchCandidateStatus->setDatabaseTable('candidates');
        $dataStatus = $searchCandidateStatus->candidateVoteResultSearch($searchField);
    }

    if (isset($_POST['refresh'])) {
        require_once '../function/Model.php';
    
        $refreshTable = true;
    }

    require_once '../function/Model.php';

    $resultCandidates = new Model();
    $resultCandidates->setDatabaseTable('candidates');
    $data = $resultCandidates->candidateVoteResult();
?>

<div class="result-container">
    <header class="result-header">
        <h1>Result</h1>
    </header>

    <div class="result-container-inner">
        <form action="" class="result-search-container" method="POST">
            <input type="hidden" name="result" value="1">

            <h2>Election Results</h2>
            <div class="input-container">
                <div class="search-container">
                    <input type="text" name="candidateSearchStatus">
                    <button type="submit" name="searchCandidateResult">
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

        <hr class="seperator-line">

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Party List</th>
                        <th>Total Votes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($searchField) && $searchField != '') : ?>
                        <?php if (isset($dataStatus) && count($dataStatus) > 0): ?>
                            <?php foreach($dataStatus as $candidatesResult): ?>
                                <tr>
                                    <td><?php echo $candidatesResult['Name'] ?></td>
                                    <td><?php echo $candidatesResult['Position'] ?></td>
                                    <td><?php echo $candidatesResult['PartyList'] ?></td>
                                    <td><?php echo $candidatesResult['MaxVotes'] ?></td>
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
                                <td><?php echo $candidatesStatus['MaxVotes'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach($data as $candidates): ?>
                            <tr>
                                <td><?php echo $candidates['Name'] ?></td>
                                <td><?php echo $candidates['Position'] ?></td>
                                <td><?php echo $candidates['PartyList'] ?></td>
                                <td><?php echo $candidates['MaxVotes'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.result-container table tbody tr');

        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>