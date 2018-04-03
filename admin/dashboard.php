<?php
    session_start();

    $pageTitle = 'Dashboard';

    if(isset($_SESSION['username'])){
        include 'init.php';
        $usersnumber = 4;  // numbers of latest member to show
        $latestmembers = getlatest('username , userid, regstatus', 'users', 'userid', $usersnumber); // get latest items function
        $itemsnumber = 3;
        $latestitems = getlatest('item_name,itemid,approve', 'items', 'itemid', $itemsnumber);
        $commentnumber = 3;
        $latestcomments = getlatest('commentid','comments','commentid',$commentnumber);

        
        ?>
        <div class="home-stats text-center">
            <div class="container">
                <h1 class="text-center">Dashboard</h1>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="stat members">
                            <i class="fa fa-users"></i>
                            <div class="info">
                                <h2>Total members</h2>
                                <span><a href="members.php"><?php echo countItems('userid', 'users'); ?></a></span>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="stat pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            <h2>Pending members</h2>
                                <span><a href="members.php?do=manage&page=pending">
                                    <?php echo countItems('userid', 'users', 'WHERE regstatus = 0'); ?>
                                </a></span>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="stat items">
                            <i class="fa fa-tag"></i>
                            <div class="info">
                                <h2>Total Items</h2>
                                <span><a href="items.php"><?php echo countItems('itemid', 'items'); ?></a></span>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="stat comments">
                            <i class="fa fa-comment"></i>
                            <div class="info">
                                <h2>Total comments</h2>
                                <span><a href="comments.php"><?php echo countItems('commentid', 'comments'); ?></a></span>
                            </div>
                        </div>    
                    </div>
                </div>    
            </div> 
        </div> 
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
<!-- latest users -->                 
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-users"></i> Least <?php echo $usersnumber ?> registered users
                            <span class="toggle pull-right">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            
                            <?php
                                echo "<ul class='list-unstyled users-list'>";
                                    foreach ($latestmembers as $user){
                                        echo "<li>";
                                            echo "<a href='members.php?do=edit&userid=" . $user['userid'] . "'>";
                                                echo "<div class='btn btn-success pull-right'><i class='fa fa-edit'></i> edit</div>";
                                            echo "</a>";
                                            if ($user['regstatus'] == 0){  
                                                echo "<a href='members.php?do=activate&userid=" . $user['userid'] . "'>";  
                                                    echo "<div class='btn btn-info pull-right'><i class='fa fa-check'></i> Activate</div>";
                                                echo "</a>";
                                            }
                                            echo $user['username'];
                                        echo "</li>";
                                        }
                                echo "</ul>";    
                            ?>
                        </div>
                    </div>
                </div>
<!-- latest items -->                 
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-users"></i> Least Added Items
                         <span class="toggle pull-right">    
                            <i class="fa fa-plus pull-right toggle"></i>
                        </span>    
                        </div>
                        <div class="panel-body">
                        <?php
                                echo "<ul class='list-unstyled users-list'>";
                                    foreach ($latestitems as $item){
                                        echo "<li>";
                                            echo "<a href='items.php?do=edit&itemid=" . $item['itemid'] . "'>";
                                                echo "<div class='btn btn-success pull-right'><i class='fa fa-edit'></i> edit</div>";
                                            echo "</a>";
                                            if ($item['approve'] == 0){  
                                                echo "<a href='items.php?do=approve&itemid=" . $item['itemid'] . "'>";  
                                                    echo "<div class='btn btn-info pull-right'><i class='fa fa-check'></i> Activate</div>";
                                                echo "</a>";
                                             }
                                            echo $item['item_name'];
                                        echo "</li>";
                                    }
                                echo "</ul>";    
                        ?>
                        </div>
                    </div>
                </div> 


            </div>
<!-- latest comments --> 
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-comments-o"></i> Least <?php echo $commentnumber ?> Comments
                            <span class="toggle pull-right">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            
                        <?php
                            $stmt = $con->prepare("SELECT 
                                        comments.*,
                                        users.username AS name
                                    FROM 
                                        comments    
                                    JOIN 
                                        users
                                    ON  
                                        users.userid = comments.user_id
                                    ORDER BY comment_date DESC    
                                    LIMIT $commentnumber");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();  

                            echo "<ul class='list-unstyled users-list'>";
                                foreach($comments as $comment){
                                        echo "<div class='comment-box'>";
                                            echo "<div class='member-n'>" . $comment['name'] . "</div>";
                                            echo "<div class='member-c'>" . $comment['comment_text'] . "</div>";
                                        echo "</div>";
                                }  
                            ?>


                        </div>
                    </div>
                </div>

            </div>

        </div> 

        <?php
        }else{
            header('location: index.php');
    }

include $temp . 'footer.php';
?>    