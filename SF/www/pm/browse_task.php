<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id$

if (!$offset || $offset < 0) {
	$offset=0;
}

//
// Memorize order by field as a user preference if explicitly specified.
// Automatically discard invalid field names.
//
if ($order) {
	if ($order=='project_task_id' || $order=='percent_complete' || $order=='summary' || $order=='start_date' || $order=='end_date' || $order=='priority' || $order=='user_name' || $order=='status_name'|| $order=='project_name') {
		if(user_isloggedin()) {
			user_set_preference('pm_task_order', $order);
		}
	} else {
		$order = false;
	}
} else {
	if(user_isloggedin()) {
		$order = user_get_preference('pm_task_order');
	}
}

if ($order) {
    //if ordering by priority, sort DESC
    //if ordering by user assigned to then use the user table
    //if ordering by status assigned to then use the project_status table

    switch ($order) {

    case 'user_name':
	$tbl = 'user';
	break;
    case 'status_name':
	$tbl = 'project_status';
	break;
    case 'project_name':
	$tbl = 'project_group_list';
	break;
    case 'priority':
	$tbl = 'project_task';
	$way = 'DESC';
	break;
    default:
	$tbl = 'project_task';
	break;
    }

    $order_by = ' ORDER BY '.$tbl.'.'.$order.' '.$way;
} else {
    $order_by = "";
}

//the default is to show 'my' tasks, not 'open' as it used to be
if (!$set) {
	/*
		if no set is passed in, see if a preference was set
		if no preference or not logged in, use open set
	*/
	if (user_isloggedin()) {
		$custom_pref=user_get_preference('pm_brow_cust'.$group_id);
		if ($custom_pref) {
			$pref_arr=explode('|',$custom_pref);
			$_assigned_to=$pref_arr[0];
			$_status=$pref_arr[1];
			$set='custom';
		} else {
			$set='my';
		}
	} else {
		$set='open';
		$_assigned_to=0;
	}
}

if ($set=='my') {
	/*
		My tasks - backwards compat can be removed 9/10
	*/
	$_status=1;
	$_assigned_to=user_getid();

} else if ($set=='custom') {
	/*
		if this custom set is different than the stored one, reset preference
	*/
	$pref_=$_assigned_to.'|'.$_status;
	if ($pref_ != user_get_preference('pm_brow_cust'.$group_id)) {
		//echo 'setting pref';
		user_set_preference('pm_brow_cust'.$group_id,$pref_);
	}
} else if ($set=='closed') {
	/*
		Closed tasks - backwards compat can be removed 9/10
	*/
	$_assigned_to=0;
	$_status='2';
} else {
	/*
		Open tasks - backwards compat can be removed 9/10
	*/
	$_assigned_to=0;
	$_status='1';
}

/*
	Display tasks based on the form post - by user or status or both
*/

//if status selected, and more to where clause
if ($_status) {
	//for open tasks, add status=100 to make sure we show all
	$status_str="AND project_task.status_id = '$_status' ";
} else {
	//no status (or any status) was chosen, so don't add it to where clause
	$status_str='';
}

//if assigned to selected, and more to where clause
if ($_assigned_to) {
	$assigned_str="AND project_assigned_to.assigned_to_id='$_assigned_to'";
	
} else {
	//no assigned to was chosen, so don't add it to where clause
	$assigned_str='';
}


//if sub_project to selected, add more to where and from clauses
if ($group_project_id) {
	$subproj_where = ' project_task.group_project_id='.$group_project_id.' AND project_group_list.group_project_id=project_task.group_project_id AND ';
	
} else {
	//no subproj was chosen so make sur it belongs to the
	//right group_id and make a join on sub projects
	$subproj_where = " project_group_list.group_id='$group_id' AND project_group_list.group_project_id=project_task.group_project_id AND ";

}



//build page title to make bookmarking easier
//if a user was selected, add the user_name to the title
//same for status
pm_header(array('title'=>'Browse Tasks '.
	(($group_project_id) ? ' In: '. pm_data_get_group_name($group_project_id) : '').
	(($_assigned_to)?' For: '.user_getname($_assigned_to):'').
	(($_status && ($_status != 100))?' By Status: '.pm_data_get_status_name($_status):'')));

$sql='SELECT project_task.priority,project_task.group_project_id,project_task.project_task_id,'.
	'project_task.start_date,project_task.end_date,project_task.percent_complete,'.
        'project_task.summary,user.user_name,project_status.status_name,'.
        'project_group_list.project_name '.
	'FROM project_group_list, project_task, project_assigned_to, user,project_status '.
	'WHERE '.$subproj_where.' project_task.project_task_id=project_assigned_to.project_task_id '.
        'AND user.user_id=project_assigned_to.assigned_to_id '.
        'AND project_status.status_id=project_task.status_id '.
	" $assigned_str $status_str ".
	$order_by .
	" LIMIT $offset,50";

$message="Browsing Custom Task List";
//echo "DBG -- $sql <BR>";
$result=db_query($sql);

/*
        creating a custom technician box which includes "any" and "unassigned"
*/

$res_tech=pm_data_get_technicians ($group_id);

$tech_id_arr=util_result_column_to_array($res_tech,0);
$tech_id_arr[]='0';  //this will be the 'any' row

$tech_name_arr=util_result_column_to_array($res_tech,1);
$tech_name_arr[]='Any';

$tech_box=html_build_select_box_from_arrays ($tech_id_arr,$tech_name_arr,'_assigned_to',$_assigned_to,true,'Unassigned');

/*

	creating a custom Sub-project box which includes "Any". We cannot
	create the infamous "100" value (None) in the sub_project table
	and make it appear as "Any" because the "Any" item
	would also show up in the select box when we modify a task. And we
	do not want a task to be associated with "None" sub-project

*/

$res_subproj = pm_data_get_subprojects($group_id);
$subproj_id_arr=util_result_column_to_array($res_subproj,0);
$subproj_id_arr[]='0';  //this will be the 'any' row

$subproj_name_arr=util_result_column_to_array($res_subproj,1);
$subproj_name_arr[]='Any';

$subproj_box=html_build_select_box_from_arrays ($subproj_id_arr,$subproj_name_arr,'group_project_id',$group_project_id,false);


/*
	Show the new pop-up boxes to select assigned to and/or status
*/
echo '<TABLE WIDTH="10%" BORDER="0" CELLPADDING="0" CELLSPACING="0"><FORM ACTION="'. $PHP_SELF .'" METHOD="GET">
	<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="'.$group_id.'">
	<INPUT TYPE="HIDDEN" NAME="set" VALUE="custom">
	<TR><TD COLSPAN="4" nowrap>Browse Tasks by:</TD></TR>
	<TR align="center" valign="bottom"><TH><b>Sub-Project</b></TH><TH><b>Assignee</b></TH><TH><b>Status</b></TH></TR>
	<TR><TD><FONT SIZE="-1">'. $subproj_box .'</FONT></TD>'.
		'<TD><FONT SIZE="-1">'. $tech_box .'</FONT></TD><TD><FONT SIZE="-1">'. pm_status_box('_status',$_status) .'</FONT></TD>'.
		'<TD><FONT SIZE="-1"><INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="Browse"></FONT></TD></TR></FORM></TABLE>';


if (db_numrows($result) < 1) {

	echo '
		<H1>No Matching Tasks found</H1>
		<P>
		<B>Add tasks using the link above</B>';
	echo db_error();
	echo '

<!-- '. $sql .' -->';
} else {

	//create a new $set string to be used for next/prev button
	if ($set=='custom') {
		$set .= '&_assigned_to='.$_assigned_to.'&_status='.$_status;
	}

	/*
		Now display the tasks in a table with priority colors
	*/

	echo '
		<br>
		<H3>'.$message.' In '. ($group_project_id ? pm_data_get_group_name($group_project_id) : 'Any Sub-project') .'</H3>';
	pm_show_tasklist($result,$offset,$set);
	echo '<P><b>* Denotes overdue tasks</b>';
	show_priority_colors_key();
	$url = "/pm/task.php?group_id=$group_id&group_project_id=$group_project_id&func=browse&set=$set&order=";
	echo '<P>Click a column heading to sort by that column, or <A HREF="'.$url.'priority">Sort by Priority</A>';

}

pm_footer(array());

?>
