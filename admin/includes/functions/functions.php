<?php


function getAllFrom($field,$table,$where = null,$and = null,$orderField,$orderBy = "ASC"){
    global $con;
    //$sql = $where == null ? '' : $sql = $where;
    $stmt = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $orderBy");
    $stmt->execute();
    $getAll = $stmt->fetchAll();
    return $getAll;
} 



/* Title page name */

function getTitle(){
    
    global $pageTitle;
    
    if(isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'default';
    }
}

/* redirect  function */

function redirectHome($msg, $url=null, $timer = 1) {

    if($url === null){
        $url = 'index.php';
        $link = 'HomePgae';
    }
    elseif($url == 'back'){
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'Pervious Page';
    }
    elseif($url == 'members'){
        $url = 'members.php';
        $link = 'members Page';
    }
    elseif($url == 'items'){
        $url = 'items.php';
        $link = 'Items Page';
    }
    else{
        $url = 'index.php';
        $link = 'HomePgae';
    }
?>
    <div class="container">
        <?php echo $msg ?>
        <div class='alert alert-info'>you will be redirected to <?php echo $link ?> in <?php echo $timer ?> seconds</div>
    </div>

<?php 
    
    header("refresh:$timer; url=$url");

    exit();
}

/*** $SELECTED = username , email , item ,..
**** $FROM     = table name (user , items)
     $VALUE    = Alaa , admin  

/* check the existance in database */

function checkExist($selected, $from, $value){
    global $con;
    global $stmt;
    $stmt = $con->prepare("SELECT $selected FROM $from WHERE $selected = ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();
    return $count;
}


/* Count items function
**** $item -> the item to count (usernames , emails)
**** $table -> the table to count from (users , products)
*/

function countItems($item, $table, $conditon = "") {
    global $con;
    global $stmt;
    $stmt = $con->prepare("SELECT COUNT($item) FROM $table $conditon");
    $stmt->execute();
    return $stmt->fetchColumn();
}

/** Get latest items Registered or Add
*** $selected -> the item to select (username , email )
**  $table    -> the table to search in
**  $oerder   -> how u like to order the resutl
**  $limit    -> the number of shown results  
*/
function getlatest($selected, $table, $order , $limit = 3){
    global $con;
    global $stmt;
    $stmt = $con->prepare("SELECT $selected FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}

?>