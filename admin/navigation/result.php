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
        $searchCandidateResult = new Model();
        $searchCandidateResult->setDatabaseTable('candidates');
        $dataResult = $searchCandidateResult->candidateVoteResultSearch($searchField);
    }

    if (isset($_POST['refresh'])) {
        require_once '../function/Model.php';
    
        $refreshTable = true;
    }

    require_once '../function/Model.php';

    $resultCandidates = new Model();
    $resultCandidates->setDatabaseTable('candidates');
    $result = $resultCandidates->candidateVoteResult();
?>




<div class="result-container">
    <header class="result-header">
        <h1>Result</h1>
    </header>

    <div class="result-container-inner">
        <form action="adminPanel.php?page=result" class="result-search-container" method="POST">
            <input type="hidden" name="result" value="1">

            <h2>Election Results</h2>
            <div class="input-container">
                <div class="search-container">
                    <input type="text" name="candidateSearchStatus" id="search">
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
            <?php if (isset($searchField) && $searchField != ''):  ?>
                <?php if (isset($dataResult) && count($dataResult) > 0): ?>
                    <?php foreach ($dataResult as $position => $candidates): ?>
                        <h2><?php echo $position; ?></h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Party List</th>
                                    <th>Total Votes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $row): ?>
                                    <tr>
                                        <td><?php echo $row['Ranking'] ?></td>
                                        <td><?php echo $row['Name'] ?></td>
                                        <td><?php echo $row['PartyList'] ?></td>
                                        <td><?php echo $row['VoteCount'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php else:  ?>
                    <p id="prompt">
                        No candidates found.
                    </p>
                <?php endif; ?>
            <?php elseif (isset($searchCandidateResult) && $searchField == ''): ?>
                <p id="prompt">No candidates found.</p>
            <?php elseif (isset($refreshTable) && $refreshTable == true): ?>
                <?php foreach ($result as $position => $candidates): ?>
                    <h2><?php echo $position; ?></h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Party List</th>
                                <th>Total Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $row): ?>
                                <tr>
                                    <td><?php echo $row['Ranking'] ?></td>
                                    <td><?php echo $row['Name'] ?></td>
                                    <td><?php echo $row['PartyList'] ?></td>
                                    <td><?php echo $row['VoteCount'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($result as $position => $candidates): ?>
                    <h2><?php echo $position; ?></h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Party List</th>
                                <th>Total Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $row): ?>
                                <tr>
                                    <td><?php echo $row['Ranking'] ?></td>
                                    <td><?php echo $row['Name'] ?></td>
                                    <td><?php echo $row['PartyList'] ?></td>
                                    <td><?php echo $row['VoteCount'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endif; ?>
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