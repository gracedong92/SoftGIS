<?php echo $this->Html->script('locationpicker'); ?>
<script>

var pathSearchUrl = "<?php echo $this->Html->url(
        array('controller' => 'paths', 'action' => 'search.json')
    ); ?>";

var markerSearchUrl = "<?php echo $this->Html->url(
        array('controller' => 'markers', 'action' => 'search.json')
    ); ?>";

var locationPicker;

var viewModel = {
    poll: new Poll(<?php echo json_encode($poll['Poll']); ?>),
    questions: ko.observableArray([
        <?php foreach ($poll['Question'] as $q): ?>
            new Question(<?php echo json_encode($q); ?>),
        <?php endforeach; ?>
    ]),
    paths: ko.observableArray(<?php echo json_encode($poll['Path']); ?>),
    markers: ko.observableArray(<?php echo json_encode($poll['Marker']); ?>),

    // List of question types
    types: [
        { id: 1, label: "Teksti" },
        { id: 2, label: "Kyllä, Ei, En osaa sanoa" },
        { id: 3, label: "1-5, En osaa sanoa" },
        { id: 4, label: "1-7, En osaa sanoa" }
    ],
    newQuestion: function() {
        var question = new Question({
            num: this.questions().length + 1
        });
        question.toggle();
        this.questions.push(question);
    }
}

function Poll(data) {
    this.id = ko.observable( data.id ? data.id : null );
    this.name = ko.observable( data.name ? data.name : null );
    this.public = ko.observable( data.public == "0" ? false : true );
    this.welcome_text = ko.observable( data.welcome_text ? data.welcome_text : null );
    this.thanks_text = ko.observable( data.thanks_text ? data.thanks_text : null );
}

function Question(data, visible) {
    // console.info(data);
    this.id = ko.observable( data.id ? data.id : null );
    this.text = ko.observable( data.text ? data.text : null );
    this.num = ko.observable( data.num ? data.num : null );
    this.type = ko.observable( data.type ? data.type : null );
    this.low_text = ko.observable( data.low_text ? data.low_text : null );
    this.high_text = ko.observable( data.high_text ? data.high_text : null );
    this.latlng = ko.observable( data.latlng ? data.latlng : null );

    // Pfft, Cake thinks 0 is false
    this.answer_location = ko.observable( 
        data.answer_location && data.answer_location != "0" ? true : false 
    );
    this.answer_visible = ko.observable( 
        data.answer_visible && data.answer_visible != "0" ? true : null 
    );
    this.comments = ko.observable( 
        data.comments && data.comments != "0" ? true : false 
    );

    this.visible = ko.observable( visible ? true : false );
}

Question.prototype.toggle = function() {
    this.visible( !this.visible() );
}

Question.prototype.pickLocation = function() {
    var me = this;
    locationPicker.locationpicker(
        "open",
        this.latlng(),
        function(newPos) {
            me.latlng( newPos );
        }
    );
}

$( document ).ready(function() {
    ko.applyBindings( viewModel );
    locationPicker = $( "#loc-picker" ).locationpicker();


    $( "#paths" ).tokenInput("/paths/search.json", {
        prePopulate: viewModel.paths(),
        preventDuplicates: true,
        onAdd: function(item) {
            viewModel.paths.push( item );
        },
        onDelete: function(item) {
            viewModel.paths.remove( item );
        }
    });

    $( "#markers" ).tokenInput("/markers/search.json", {
        prePopulate: viewModel.markers(),
        preventDuplicates: true,
        onAdd: function(item) {
            viewModel.markers.push( item );
        },
        onDelete: function(item) {
            viewModel.markers.remove( item );
        }
    });

    $( "#saveButton" ).click(function() {
        var data = ko.toJSON(viewModel);
        $( "#data" ).val( data );
    });
});

</script>


<h1>Kysely</h1>
<!-- Form -->
<div class="input text">
    <label>Nimi</label>
    <input type="text" data-bind="value: poll.name" />
</div>

<div class="input textarea">
    <label>Tervetuloateksti</label>
    <textarea data-bind="value: poll.welcome_text" rows="6"></textarea>
</div>

<div class="input textarea">
    <label>Kiitosteksti</label>
    <textarea data-bind="value: poll.thanks_text" rows="6"></textarea>
</div>

<div class="input checkbox">
    <input type="checkbox" data-bind="checked: poll.public"/>
    <label for="PollPublic">Kaikille avoin</label>
</div>

<div class="input text">
    <label>Reitit</label>
    <input type="text" id="paths" />
</div>

<div class="input text">
    <label>Merkit</label>
    <input type="text" id="markers" />
</div>

<div class="input">
    <label>Kysymykset</label>
    <ul id="questions" 
        data-bind="template: {
            name: 'questionTmpl',
            foreach: questions
        }">
    </ul>
    <button type="button" id="create-question" data-bind="click: newQuestion">
        Luo uusi kysymys
    </button>
</div>

<form method="post">
    <input type="hidden" name="data" id="data"/>
    <button type="submit" id="saveButton">
        Tallenna kysely
    </button>
</form>


<div id="loc-picker"></div>


<!-- Question Template -->
<script type="text/x-jquery-tmpl" id="questionTmpl">

<li class="question">
    <table class="header">
        <tr>
            <td class="num" data-bind="text: num"></td>
            <td>&nbsp;<span class="text" data-bind="text: text"></span></td>
            <td class="button" data-bind="click: toggle">
                <div class="expand">Näytä</div>
            </td>
        </tr>
    </table>
    <div class="details" data-bind="visible: visible">

        <div class="input textarea">
            <label>Kysymys</label>
            <textarea class="text" data-bind="value: text"></textarea> 
        </div>

        <div class="input select">
            <label>Vastaus</label>
            <select data-bind="options: viewModel.types,
                optionsText: 'label', optionsValue: 'id',
                value: type" />
        </div>

        <div class="input text" data-bind="visible: type != 1">
            <label>Ääripäät</label>
            <input type="text" class="small" data-bind="value: low_text"/>
            Pienin<br />
            <input type="text" class="small" data-bind="value: high_text" />
            Suurin
        </div>

        <div class="input text">
            <label>Sijainti</label>
            <input type="text" 
                class="latlng"
                data-bind="value: latlng"/>
            <button class="pick-location" 
                type="button"
                data-bind="click: pickLocation">
                Valitse
            </button>
        </div>

        <div class="input checkbox">
            <input type="checkbox"
                data-bind="checked: answer_location" />
            <label>Sijainti vastaajalta</label>
        </div>

        <div class="input checkbox">
            <input type="checkbox"
                data-bind="checked: answer_visible" />
            <label>Yleiset vastaukset</label>
        </div>

        <div class="input checkbox">
            <input type="checkbox"
                data-bind="checked: comments" />
            <label>Vastausten kommentointi</label>
        </div>
    </div>
</li>
</script>