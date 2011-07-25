<?/*<h1><?php echo $me['Author']['username']; ?></h1>*/?>

<h1>Omat kyselyt</h1>
<table class="list">
    <thead>
        <tr>
            <th>Nimi</th>
            <th>Julkaistu</th>
            <th>Vastauksia</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($me['Poll'] as $poll): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link(
                        $poll['name'],
                        array(
                            'controller' => 'polls', 
                            'action' => 'edit',
                            $poll['id']
                        )
                    ); ?>
                </td>
                <td>
                    <?php if (empty($poll['publisched'])) {
                        echo 'Ei';
                    } else {
                        echo $poll['published'];
                    }; ?>
                </td>
                <td>-</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>