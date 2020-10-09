<?php // TASK: You have team with workers. Every worker can make Job and write title and count spend time. After time spending he get price for this time (1$/sec). At end of day he can get sum and clear all records from system

session_start();
if ( ! isset($_SESSION['tasks'])) $_SESSION['tasks'] = Array();
if (isset($_POST['add_task'])) {
    $_POST['name'] = strip_tags($_POST['name']);
    $_POST['price'] = strip_tags($_POST['price']);
    $_SESSION['tasks'][] = Array( 'name' => $_POST['name'], 'price' => $_POST['price'] );
    echo json_encode($_SESSION['tasks'], JSON_PRETTY_PRINT);
} elseif (isset($_POST['day_end'])) {
    $sum = 0;
    foreach ($_SESSION['tasks'] as $task) $sum += $task['price'];
    echo $sum;
    $_SESSION['tasks'] = Array();
} else {
?><html>
<head>
    <title>
        Task
    </title>
</head>
<body>
    <div class="run_interface" style="position: fixed; bottom:0; width:100%; height: 100px; background: white; border-top: 1px solid silver; text-align: center;">
        <input class="task_name" type="text">
        <br>
        <input type="button" value="Start / Go" class="task_start">
        <input type="button" value="Pause" class="task_pause">
        <input type="button" value="Finish" class="task_finish">
        <br>
        <b>Task Time</b> <span class="task_time">0</span> sec
    </div>
    <div class="works_list" style="border: 1px solid silver; width: 90%; margin: 10px; padding: 10px;">
        <?php foreach ($_SESSION['tasks'] as $task) { ?>
            <div class="work_done"><?=$task['name']?> - <span class="price_done"><?=$task['price']?></span></div>
        <?php } ?>
    </div>
    <br>
    <input type="button" value="End of Day" class="day_end">
</body><script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script>
    let task_time = 0;
    let time_counter;
    let time_counter_is_on = false;
    $('.task_start').on('click', function(){
        if (!time_counter_is_on) {
            time_counter = setInterval(function(){

                $('.task_time').html( parseInt($('.task_time').html()) + 1 );

            }, 1000);
            time_counter_is_on = true;
        }
    });
    $('.task_pause').on('click', function(){
        clearInterval(time_counter);
        time_counter_is_on = false;
    });
    $('.task_finish').on('click', function(){
        clearInterval(time_counter);
        time_counter_is_on = false;

        let new_div = '<div class="work_done">'+ $('.task_name').val() +' - <span class="price_done">' + ( parseInt( $('.task_time').html() ) * 1 ) + '</span></div>';

        $.post('test.php', { add_task : 1, name : $('.task_name').val(), price : parseInt( $('.task_time').html() ) },
            function(data){
                console.log(data);
            }
        );

        $('.task_time').html('0');
        $('.task_name').val('');

        $('.works_list').append(new_div);
    });

    $('.day_end').on('click', function(){
        $.post('test.php', { day_end : 1 },
            function(data){
                alert('TO PAY: ' + data + ' $ ');
                location.reload();
            }
        );
    });
</script>
</html>
<?php }