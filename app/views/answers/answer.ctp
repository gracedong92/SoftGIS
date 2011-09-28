<?php echo $this->Html->script('json2'); ?>
<?php echo $this->Html->script('spine'); ?>
<?php echo $this->Html->script('controllers/map'); ?>
<?php echo $this->Html->script('models/poll'); ?>
<?php echo $this->Html->script('models/question'); ?>
<?php echo $this->Html->script('answerApp'); ?>

<script>
var markerIconPath = "/markericons/";
var answerApp;
$( document ).ready(function() {
    var data = <?php echo json_encode($poll); ?>;

    $.template("questionTmpl", $("#questionTmpl"));
    $.template("welcomeTmpl", $("#welcomeTmpl"));

    answerApp = AnswerApp.init({
        el: $("body"),
        data: data
    });  

    // Help toggle
    $( ".help" ).hide();
    $( "#toggleHelp" ).click(function() {
        $( ".help" ).fadeToggle();
        return false;
    });
});
</script>

<div class="answerMenu">
    <?php echo $this->Html->link(
        'Apua',
        '#help',
        array('class' => 'button', 'id' => 'toggleHelp')
    ); ?>
</div>

<div class="help">
    <p>Tähän ohjeet vastaamiseen</p>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
</div>

<script id="welcomeTmpl" type="text/x-jquery-tmpl">
    <h3>
        ${name}
    </h3>
    <div class="welcomeText">
        ${welcome_text}
    </div>
    <div class="answerNav">
        <button type="button" class="start">
            Aloita kysely
        </button>
    </div>
</script>

<script id="questionTmpl" type="text/x-jquery-tmpl">
    <h3>${text}</h3>
    <div class="answer-field">
        <div class="input">
            {{if type == 1}}
                <textarea name="text"></textarea>
            {{else type == 2}}
                <input type="radio" name="text" value="Kyllä"/>Kyllä
                <input type="radio" name="text" value="Ei"/>Ei
                <input type="radio" name="text" value="En osaa sanoa"/>En osaa sanoa
            {{else type == 3}}
                ${low_text}
                <input type="radio" name="text" value="1"/>
                <input type="radio" name="text" value="2"/>
                <input type="radio" name="text" value="3"/>
                <input type="radio" name="text" value="4"/>
                <input type="radio" name="text" value="5"/>
                ${high_text}
                <input type="radio" name="text" value="En osaa sanoa"/>En osaa sanoa
            {{else type == 4}}
                ${low_text}
                <input type="radio" name="text" value="1"/>
                <input type="radio" name="text" value="2"/>
                <input type="radio" name="text" value="3"/>
                <input type="radio" name="text" value="4"/>
                <input type="radio" name="text" value="5"/>
                <input type="radio" name="text" value="6"/>
                <input type="radio" name="text" value="7"/>
                ${high_text}
                <input type="radio" name="text" value="En osaa sanoa"/>En osaa sanoa
            {{/if}}
        </div>
    </div>
    <div class="answerNav">
        <button type="button" class="submit">Seuraava kysymys</button>
    </div>
</script>
<div id="question" class="answer"></div>
<div class="answer">
    <div id="map" class="map"></div>
</div>
<form method="POST" 
    action="<?php echo $this->Html->url(array('action' => 'finish')); ?>" 
    id="postForm">
    <input type="hidden" id="dataField" name="data"/>
</form>
