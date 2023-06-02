<?php
require_once "UI/header.php";
require_once "include/router.php";

         
$overDue=0;
$overDue = CountDateWithTodayOnCollection($stats["projects"],"DueDate","<");
$thisDay=0;
$thisDay = CountDateWithTodayOnCollection($stats["projects"],"DueDate","==");
$thisWeek=0;
$thisWeek = CountDatesInThisWeekOnCollection($stats["projects"],"DueDate");
$users = json_decode($users,true);

$aoverDue=0;
$aoverDue = CountDateWithTodayOnCollection($stats["assignments"],"DueDate","<");
$athisDay=0;
$athisDay = CountDateWithTodayOnCollection($stats["assignments"],"DueDate","==");
$athisWeek=0;
$athisWeek = CountDatesInThisWeekOnCollection($stats["assignments"],"DueDate");

$activeAssignments  =0;
$activeProjects  =0;
foreach($stats['assignments'] as $assignment)
{
    if($assignment['StatusId']!="4")$activeAssignments++;
}
foreach($stats['projects'] as $project)
{
    if($project['IsArchived']!="1")$activeProjects++;
}

?>
  <nav class="purple darken-4">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo"><img src="UI/assets/logo-white.png" style="width:90%" ></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a><span class="white-text badge blue"><?=$activeProjects?>/<?=count($stats["projects"])?> Projects</span></a></li>
          <li><a><span class="white-text badge orange"><?=$activeAssignments?>/<?=count($stats["assignments"])?> Assignments</span></a></li>
          <li> Hey <?=$user["Username"]?>!</li>
        <li><a href="/logout">logout?</a></li>
      </ul>
    </div>
  </nav>
<div class="container">


    <h3>Hight Priority Assignments</h3>
    <div class="row">
        <div class="col s4">
            <i class="small red-text material-icons">assignment</i> Over Due <a href=""><span class="counter"><?=$aoverDue?></span></a>
        </div>
        <div class="col s4">
            <i class="small yellow-text material-icons">assignment</i> Due Today <a href=""><span class="counter"><?=$athisDay?></span></a>
        </div>
        <div class="col s4">
            <i class="small blue-text material-icons">assignment</i> Due This Week <a href=""><span class="counter"><?=$athisWeek?></span></a>
        </div>
    </div>
    <h3>Hight Priority Projects</h3>
    <div class="row">
        <div class="col s4">
            <i class="small red-text material-icons">create_new_folder</i> Over Due <a href=""><span class="counter"><?=$overDue?></span></a>
        </div>
        <div class="col s4">
            <i class="small yellow-text material-icons">create_new_folder</i> Due Today <a href=""><span class="counter"><?=$thisDay?></span></a>
        </div>
        <div class="col s4">
            <i class="small blue-text material-icons">create_new_folder</i> Due This Week <a href=""><span class="counter"><?=$thisWeek?></span></a>
        </div>
    </div>
    <h3>My Active Projects</h3>

    <ul class="collapsible popout">

        <?php foreach($UserData["projects"] as $project):?>
            <li>
            <div class="collapsible-header">
                <ul class="detail-items">
                    <li>
                        <i class="material-icons">create_new_folder</i>
                    </li>
                    <li>
                        <?=$project["Name"]?>
                    </li>
                    <li>
                        <?=logDate($project["DueDate"])?>
                    </li>
                </ul>
                <ul class="detail-items left">
                    <li>
                        <span  class="waves-effect waves-light T modal-trigger" href="#updateProject" ><i data-project-id="<?=$project["Id"]?>" class="material-icons purple-text darken-4 T">edit</i></span>
                    </li>
                    <li>
                    <span  class="waves-effect waves-light T modal-trigger" href="#modal2" ><i data-project-id="<?=$project["Id"]?>" class="material-icons purple-text darken-4 T">assignment</i></span>
                    </li>
                    <li>
                    <span  class="waves-effect waves-light" onclick="deleteProject(<?=$project["Id"]?>)" ><i data-project-id="<?=$project["Id"]?>" class="material-icons red-text darken-4 T">delete</i></span>
                    </li>
                </ul>
            </div>
        <div class="collapsible-body">
            <?php foreach ($project["assignments"] as $assignment):?>
                
                <ul class="detail-items <?=str_replace(" ","-",$assignment["Status"])?>">
                    <li>
                        <i class="material-icons">assignment</i>
                    </li>
                    <li>
                        <?=$assignment["Name"]?>
                    </li>
                    <li>
                        <?=logDate($assignment["DueDate"])?>
                    </li>
                    <li>
                        <i class="material-icons">person</i> <?=logDate($assignment["Username"])?>
                    </li>
                    <li>
                    <i class="material-icons">sync</i><?=logDate($assignment["Status"])?>
                    </li>
                    <li>
                    <span  class="waves-effect waves-light T modal-trigger" href="#updateAssignment" ><i class="material-icons purple-text">edit</i></span>
                    </li>
                    <li>
                    <span  class="waves-effect waves-light" onclick="deleteAssignment(<?=$assignment['Id']?>)" ><i data-project-id="<?=$project["Id"]?>" class="material-icons red-text darken-4 T">delete</i></span>
                    </li>
                </ul>
            <?php endforeach ?>
            <?php if(count($project["assignments"])==0): ?>
                    <p class="amber lighten-2"> No Assignment Yet You can add them by clicking on the  [<i class="material-icons tiny">assignment</i>] in the project details</p>
                <?php endif; ?>
        </div>
        </li>
        <?php endforeach?>
        
    </ul>
    <!-- Modal Trigger -->
    <a class="btn-floating btn-large waves-effect waves-light  btn modal-trigger" href="#modal1"><i class="material-icons">create_new_folder</i></a>
    <h3>My Active Assignments</h3>

    <ul class="collection unfinished">
        <?php foreach($UserData["assignments"] as $assignment):?>
            <li class="collection-item">
                <ul class="<?=str_replace(" ","-",$assignment["Status"])?> detail-items">
                    <li><?=$assignment["Owner"]?></li>
                    <li><?=$assignment["Project"]?></li>
                    <li><?=$assignment["Name"]?></li>
                    <li><?=$assignment["Status"]?></li>
                    <li><?=logDate($assignment["DueDate"])?></li>
                    <li><a class='dropdown-trigger btn' href='#' data-target='dropdown1'>Mark As</a></li>
                </ul>
                <!-- Dropdown Structure -->
                <ul id='dropdown1' class='dropdown-content' style="width:20%;">
                    <?php foreach($UserData["statuses"] as $status):?>
                    <li><a onclick="changeStatus(<?=$status['Id']?>,<?=$assignment['Id']?>)" data-id="<?=$status["Id"]?>" ><?=$status["Label"]?></a></li>
                    <?php endforeach?>
                </ul>
            </li>
        <?php endforeach ?>
    </ul>
</div>


    <!-- New Project -->
    <div id="modal1" class="modal">
    <div class="modal-content">
        <h4>Add New Project</h4>
        <form action="/addProject" name="newProject" method="post">
            <div class="form-group">
                <label for="Name">Name</label>
                <input name="Name" type="text" class="form-control" id="Name" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
            <div class="form-group">
                <label for="DueDate">Due Date</label>
                <input name="DueDate" type="date" class="form-control" id="DueDate" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-info"><i class="material-icons">add_circle_outline</i></button>
        </div>
    </form>
    </div>

        <!-- Update Project -->
    <div id="updateProject" class="modal">
    <div class="modal-content">
        <h4>Update Project</h4>
        <form action="/UpdateProject" name="newProject" method="post">
            <div class="form-group">
                <label for="Name">Name</label>
                <input name="Name" type="text" class="form-control" id="Name" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
            <div class="form-group">
                <label for="DueDate">Due Date</label>
                <input name="DueDate" type="date" class="form-control" id="DueDate" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-info"><i class="material-icons">refresh</i></button>
        </div>
    </form>
    </div>

        <!-- New Assignment -->
    <div id="modal2" class="modal" style="height:100%">
        <div class="modal-content">
            <h4>Add New Assignment</h4>
            <form action="/addAssignment" name="newAssignment" method="post">
                <div class="form-group">
                    <label for="assignmentName">Name</label>
                    <input name="Name" type="text" class="form-control" id="assignmentName" aria-describedby="NameHelp" placeholder="Enter Assignment Name" required>
                </div>
                <div class="form-group">
                    <label for="assignmentDate">Due Date</label>
                    <input name="DueDate" type="date" class="form-control" id="assignmentDate" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
                </div>
                <div class="form-group">
                    <label for="assignmentDesc">Description</label>
                    <textarea name="Description" type="date" class="form-control materialize-textarea" id="assignmentDesc" aria-describedby="NameHelp" placeholder="Assignment Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="UserId">Assignee</label>
                    <div class="input-field col s12">
                        <select name="UserId" id="UserId">
                            <option value="" disabled selected>Choose your Assignee</option>
                            <?php foreach ($users as $u): ?>
                                <option name="userOption" value="<?=$u["id"]?>"><?=$u["Username"]?></option>
                            <?php endforeach?>
                            
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><i class="material-icons">add_circle_outline</i></button>
                </div>
            </form>
    </div>


    <div id="updateAssignment" class="modal">
    <div class="modal-content">
        <h4>Update Assignment</h4>
        <form action="/UpdateProject" name="newProject" method="post">
            <div class="form-group">
                <label for="Name">Name</label>
                <input name="Name" type="text" class="form-control" id="Name" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
            <div class="form-group">
                <label for="DueDate">Due Date</label>
                <input name="DueDate" type="date" class="form-control" id="DueDate" aria-describedby="NameHelp" placeholder="Enter Project Name" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-info"><i class="material-icons">refresh</i></button>
        </div>
    </form>
    </div>