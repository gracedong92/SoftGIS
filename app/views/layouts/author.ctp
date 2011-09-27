<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php __('SoftGIS'); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <?php
        echo $this->Html->meta('icon');

        // echo $this->Html->css('cake.generic');
        echo $this->Html->css('poll');
        echo $this->Html->css('jquery.qtip.min');
        echo $this->Html->css('jquery-ui-1.8.14.custom');
        // echo $this->Html->css('jquery.meow');
        // echo $this->Html->css('smoke');
        echo $this->Html->css('token-input');
        echo $this->Html->css('ui.spinner');
        
        echo $this->Html->script('http://maps.google.com/maps/api/js?libraries=geometry&sensor=false');
        echo $this->Html->script('underscore-min');
        echo $this->Html->script('jquery-1.5.1.min');
        echo $this->Html->script('jquery-ui-1.8.14.custom.min');
        echo $this->Html->script('jquery.ui.datepicker-fi') ;
        echo $this->Html->script('ui.spinner.min');
        echo $this->Html->script('jquery.qtip.min');
        echo $this->Html->script('jquery.tmpl.min');
        echo $this->Html->script('knockout-1.2.1.debug');
        // echo $this->Html->script('knockout-1.2.1');
        echo $this->Html->script('knockout.mapping');
        // echo $this->Html->script('jquery.meow');
        // echo $this->Html->script('smoke');
        echo $this->Html->script('jquery.tokeninput');
        echo $this->Html->script('jscolor');

        echo $scripts_for_layout;
    ?>
    <script>

$( document ).ready(function() {
    $('a[title]').qtip({
        position: {
            my: "right center",
            at: "left center"
        }
    });
});

    </script>
</head>
<body>
    <div id="meows"></div>
    <div id="container">
        <div id="header">
            <h1>Soft-GIS</h1>
        </div>
        <?php echo $this->Session->flash(); ?>
        <div id="navbar">
            <ul>
                <li>
                    <?php echo $this->Html->link(
                        'Omat kyselyt',
                        array('controller' => 'polls', 'action' => 'index'),
                        array('class' => 'button')
                    );?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Luo uusi kysely',
                        array('controller' => 'polls', 'action' => 'modify'),
                        array('class' => 'button')
                    );?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Luo karttamerkki',
                        array('controller' => 'markers', 'action' => 'add'),
                        array('class' => 'button')
                    );?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Tuo reitti',
                        array('controller' => 'paths', 'action' => 'import'),
                        array('class' => 'button')
                    );?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Kirjaudu ulos',
                        array('controller' => 'authors', 'action' => 'logout'),
                        array('class' => 'button')
                    );?>
                </li>
            </ul>
        </div>
        <div id="content">


            <?php echo $content_for_layout; ?>

        </div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>