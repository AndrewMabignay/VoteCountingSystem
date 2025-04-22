<?php
    require_once '../function/Model.php';

    $resultCandidates = new Model();
    $resultCandidates->setDatabaseTable('candidates');
    $data = $resultCandidates->candidateVoteResult();
?>

<div class="result-container">
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
            <?php foreach($data as $candidates): ?>
                <tr>
                    <td><?php echo $candidates['Name'] ?></td>
                    <td><?php echo $candidates['Position'] ?></td>
                    <td><?php echo $candidates['PartyList'] ?></td>
                    <td><?php echo $candidates['MaxVotes'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>