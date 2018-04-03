<?php


function getAllFrom($field,$table,$where = null,$and = null,$orderField,$orderBy = "ASC"){
    global $con;
    //$sql = $where == null ? '' : $sql = $where;
    $stmt = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $orderBy");
    $stmt->execute();
    $getAll = $stmt->fetchAll();
    return $getAll;
} 




/* function to get items or users from any table *

function getAllFrom($table,$where = null,$orderBy){
    global $con;
    $sql = $where == null ? '' : $sql = $where;
    $stmt = $con->prepare("SELECT * FROM $table $sql ORDER BY $orderBy ASC");
    $stmt->execute();
    $getAll = $stmt->fetchAll();
    return $getAll;
} */


// function to get the items from items table

function getItems($where,$value,$approve = null){
    global $con;
    $sql = $approve == null ? '' : $aprrove = 'AND approve = 1';
    $stmt = $con->prepare("SELECT * FROM items WHERE $where=? $sql ORDER BY itemid DESC");
    $stmt->execute(array($value));
    $items = $stmt->fetchAll();
    return $items;
}

function userActivation($value){
    global $con;
    $stmtx = $con->prepare("SELECT username,regstatus FROM users WHERE username=? AND regstatus=0");
    $stmtx->execute(array($value));
    $status = $stmtx->rowCount();
    return $status;
}

/* end front end functions */

/* back end functions */
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

function redirectHome($msg, $url=null, $timer = 3) {

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

function checkExist($field, $table, $value){
    global $con;
    global $stmt;
    $stmt = $con->prepare("SELECT $field FROM $table WHERE $field = ?");
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