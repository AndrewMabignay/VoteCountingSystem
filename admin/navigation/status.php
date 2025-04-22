<?php
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