<?php

if (!empty($template)) {
    $i = '${i}';
    $id = null; 
    $num = '${num}';
    $text = '${text}';
    $type = '1';
    $latlng = '${latlng}';
    $low_text = '${low_text}';
    $high_text = '${high_text}';
    $answer_location = false;
    $answer_visible = false;
    $comments = false;
}   

$types = array(
    '1' => 'Teksti',
    '2' => 'Kyllä, Ei, En osaa sanoa',
    '3' => '1-5, En osaa sanoa',
    '4' => '1-7, En osaa sanoa'
);
?>

<li class="question">
    <?php if (!empty($id)): ?>
        <input type="hidden"
            value="<?php echo $id; ?>"
            name="data[Question][<?php echo $i; ?>][id]"/>
    <?php endif; ?>
    <input type="hidden"
        class="num"
        value="<?php echo $num; ?>"
        name="data[Question][<?php echo $i; ?>][num]"/>
    <table class="header">
        <tr>
            <td class="num"><?php echo $num; ?></td>
            <td>&nbsp;<span class="text"><?php echo $text; ?></span></td>
            <td class="button">
                <button type="button" class="expand">Näytä</button>
            </td>
        </tr>
    </table>
    <table class="details" style="display:none; ">
        <tr>
            <th>Kysymys</th>
            <td>
                <textarea class="text" 
                    name="data[Question][<?php echo $i; ?>][text]"><?php echo $text; ?></textarea>
            </td>
        </tr>
        <tr>
            <th>Vastaus</th>
            <td>
                <select name="data[Question][<?php echo $i; ?>][type]">
                    <?php foreach ($types as $v =>$l) {
                        echo '<option value="' . $v . '"';
                        if ($type == $v) {
                            echo ' selected="selected"';
                        }  
                        echo '>' . $l . '</optoin>';
                    }; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Ääripäät</th>
            <td>
                <input type="text" class="small"
                    value="<?php echo $low_text; ?>"
                    name="data[Question][<?php echo $i; ?>][low_text]"/>
                Pienin<br />
                <input type="text" class="small"
                    value="<?php echo $high_text; ?>"
                    name="data[Question][<?php echo $i; ?>][high_text]"/>
                Suurin
        </tr>
        <tr>
            <th>Sijainti</th>
            <td>
                <input type="text" 
                    class="latlng"
                    value="<?php echo $latlng; ?>"
                    name="data[Question][<?php echo $i; ?>][latlng]"/>
                <button class="pick-location" type="button">Valitse</button>
            </td>
        </tr>
        <tr>
            <th>Sijainti vastaajalta</th>
            <td>
                <input type="checkbox"
                    name="data[Question][<?php echo $i; ?>][answer_location]"
                    <?php if (!empty($answer_location)): ?>
                        checked="checked" 
                    <?php endif; ?> />
            </td>
        </tr>
        <tr>
            <th>Yleiset vastaukset</th>
            <td>
                <input type="checkbox"
                    name="data[Question][<?php echo $i; ?>][answer_visible]"
                    <?php if (!empty($answer_visible)): ?>
                        checked="checked" 
                    <?php endif; ?> />
            </td>
        </tr>
        <tr>
            <th>Vastausten kommentointi</th>
            <td>
                <input type="checkbox"
                    name="data[Question][<?php echo $i; ?>][comments]"
                    <?php if (!empty($comments)): ?>
                        checked="checked" 
                    <?php endif; ?> />
            </td>
        </tr>
    </table>
</li>