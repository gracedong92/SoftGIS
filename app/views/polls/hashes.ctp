<h1>Hashit</h1>
<h2>Luo uusia</h2>
<form method="POST" 
    action="<?php echo $this->Html->url(
            array('action' => 'generatehashes', $pollId)
        ); ?>">
    <input type="text" value="2" name="data[count]"/>
    <button type="submit">Luo</button>
</form>

<h2>Luodut</h2>
<table class="list">
    <thead>
        <tr>
            <th>Hash</th>
            <th>Url</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hashes as $hash): ?>
            <tr class="<?php echo $hash['Hash']['used'] ? 'red' : 'green'; ?>">
                <td><?php echo $hash['Hash']['hash']; ?></td>
                <td>
                    <?php echo SERVER . $this->Html->url(
                        array(
                            'controller' => 'answers',
                            'action' => 'poll',
                            $hash['Hash']['poll_id'],
                            $hash['Hash']['hash']
                        )
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
