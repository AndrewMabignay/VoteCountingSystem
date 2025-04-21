<?php
    require_once '../function/Model.php';

    $listCandidates = new Model();
    $listCandidates->setDatabaseTable('candidates');
    $data = $listCandidates->candidateList();
?>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $candidates): ?>
                <tr>
                    <td><?php echo $candidates['Name'] ?></td>
                    <td><?php echo $candidates['VoteCount'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>