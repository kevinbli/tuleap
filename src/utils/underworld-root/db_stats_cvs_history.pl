#!/usr/bin/perl
##
## db_cvs_history.pl
##
## NIGHTLY SCRIPT
##
## Pulls the parsed CVS datafile (generated by cvs_history_parse.pl ) from the
## cvs server, and parses it into the database
##
## Written by Matthew Snelham <matthew@valinux.com>
##
#use strict; ## annoying include requirements
use DBI;
use Time::Local;
use POSIX qw( strftime );
require("../include.pl");  # Include all the predefined functions
&db_connect;

my ($logfile, $sql, $res, $temp, %groups, $group_id, $errors );
my ($sql_del, $res_del, %users, $user_id); # LJ for CodeX
my $verbose = 1;

##
## Set begin and end times (in epoch seconds) of day to be run
## Either specified on the command line, or auto-calculated
## to run yesterday's data.
##
if ( $ARGV[0] && $ARGV[1] && $ARGV[2] ) {

	$day_begin = timegm( 0, 0, 0, $ARGV[2], $ARGV[1] - 1, $ARGV[0] - 1900 );
	$day_end = timegm( 0, 0, 0, (gmtime( $day_begin + 86400 ))[3,4,5] );

} else {

	   ## Start at midnight last night.
	$day_end = timegm( 0, 0, 0, (gmtime( time() ))[3,4,5] );
	   ## go until midnight yesterday.
	$day_begin = timegm( 0, 0, 0, (gmtime( time() - 86400 ))[3,4,5] );

}

   ## Preformat the important date strings.
$year   = strftime("%Y", gmtime( $day_begin ) );
$mon    = strftime("%m", gmtime( $day_begin ) );
$week   = strftime("%U", gmtime( $day_begin ) );    ## GNU ext.
$day    = strftime("%d", gmtime( $day_begin ) );
print "Running week $week, day $day month $mon year $year \n" if $verbose;

# LJ Day YYYYMMDD used in the group_cvs_full_history table
$day_date = "$year$mon$day";


   ## We'll pull down the parsed CVS log from the CVS server via http?! <sigh>
# LJ print "Pulling down preprocessed logfile from cvs1...\n" if $verbose;
# LJ $logfile = "/tmp/cvs_history.txt";
# LJ unlink("$logfile");
# LJ `wget -q -O $logfile http://cvs1/cvslogs/$year/$mon/cvs_traffic_$year$mon$day.log`;
# LJ print `ls -la $logfile`;

# LJ In the current version we get the pre-processed CVS log file on the 
# local machine (no separate CVS server

$logfile = "$codendi_log/cvslogs/$year/$mon/cvs_traffic_$year$mon$day.log";
print "Pulling down preprocessed logfile from $logfile...\n" if $verbose;


   ## Now, we will pull all of the project ID's and names into a *massive*
   ## hash, because it will save us some real time in the log processing.
print "Caching group information from groups table.\n" if $verbose;
$sql = "SELECT group_id,unix_group_name FROM groups";
$res = $dbh->prepare($sql);
$res->execute();
while ( $temp = $res->fetchrow_arrayref() ) {
	$groups{${$temp}[1]} = ${$temp}[0];
}

# LJ And we now do the same for users since we log stats about
# users as well in CodeX (See group_cvs_full_history table)
print "Caching user information from user table.\n" if $verbose;
$sql = "SELECT user_id,user_name FROM user";
$res = $dbh->prepare($sql);
$res->execute();
while ( $temp = $res->fetchrow_arrayref() ) {
        ${$temp}[1] =~ tr/A-Z/a-z/; # Unix users are lower case only
	$users{${$temp}[1]} = ${$temp}[0];
}


   ## begin parsing the log file line by line.
print "Parsing the information into the database...\n" if $verbose;
# LJ New version below
# open( LOGFILE, $logfile ) or die "Cannot open /tmp/boa_stats.txt";
open( LOGFILE, $logfile ) or die "Cannot open $logfile";

# LJ Now that open was succesful make sure that we delete all the rows
# in the group_cvs_full_history for that day so that his day is not 
# twice in the table in case of a rerun.
#
# Now that there exist a new column cvs_browse that is not filled by
# this script we need to be a bit more delicate not deleting it.
#$sql_del = "DELETE FROM group_cvs_full_history WHERE day='$day_date'";
#$res_del = $dbh->do($sql_del);

while(<LOGFILE>) {
  chomp($_);

  ## (G|U|E)::proj_name::user_name::checkouts::commits::adds
  my ($type, $group, $user, $checkouts, $commits, $adds) = split( /::/, $_, 6 );
	if ( $_ =~ /^G::/ ) {
		
		$group_id = $groups{$group};

		if ( $group_id == 0 ) {
			print STDERR "$_";
			print STDERR "db_cvs_history.pl: bad unix_group_name \'$group\' \n";
		}
			
		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_checkouts','" . $checkouts . "')";
		$dbh->do( $sql );
		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_commits','" . $commits . "')";
		$dbh->do( $sql );

		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_adds','" . $adds . "')";
		$dbh->do( $sql );

        } elsif ( $_ =~ /^U::/ ) {

	  # LJ It is a per user per group statistic so feed the
	  # group_cvs_full_history table (CodeX specific)
	  
	  $group_id = $groups{$group};

	  if ( $group_id == 0 ) {
	    print STDERR "$_";
	    print STDERR "db_cvs_history.pl: bad unix_group_name \'$group\' \n";
	  }

	  $user_id = $users{$user};

	  if ( $user_id == 0 ) {
	    print STDERR "$_";
	    print STDERR "db_cvs_history.pl: bad user_name \'$user\' \n";
	  }


	  ## test first if we have already a row for group_id, user_id, day_date that contains
          ## info on cvs browsing activity.
	  $sql_search = "SELECT * FROM group_cvs_full_history WHERE group_id=$group_id AND user_id=$user_id AND day='$day_date'";
          $search_res = $dbh->prepare($sql_search);
	  $search_res->execute();
          if ($search_res->rows > 0) {
            $sql = "UPDATE group_cvs_full_history SET cvs_commits='$commits',cvs_adds='$adds',cvs_checkouts='$checkouts' WHERE group_id=$group_id AND user_id=$user_id AND day='$day_date'";
            $dbh->do($sql);
	  } else {
	    $sql = "INSERT INTO group_cvs_full_history
			(group_id,user_id,day,cvs_commits,cvs_adds,cvs_checkouts)
			VALUES ('$group_id', '$user_id', '$day_date', '$commits', '$adds', '$checkouts ')";
	    $dbh->do($sql);
          }

	} elsif ( $_ =~ /^E::/ ) {
		$errors++;
	}

}
close( LOGFILE );

# LJ Finally feed the cvs_group_history table with the sum of commits
# and adds per project and per developer. First since the beginning of
# the project and then for the last 7 days.

print "Updating cvs_commits and cvs_adds in group_cvs_history...\n" if $verbose;
# LJ delete existing data first
$sql = "DELETE from group_cvs_history";
$dbh->do($sql);

$sql = "INSERT INTO group_cvs_history 
             SELECT group_cvs_full_history.group_id ,
                         user.user_name, 
                         SUM(cvs_commits) AS cvs_commits,
                         0 AS cvs_commits_wk,
                         SUM(cvs_adds) AS cvs_adds,
                         0 AS cvs_adds_wk 
            FROM group_cvs_full_history,user_group,user
            WHERE user_group.user_id=group_cvs_full_history.user_id AND 
                       group_cvs_full_history.group_id=user_group.group_id AND
                       user.user_id=group_cvs_full_history.user_id
          GROUP BY group_cvs_full_history.group_id, group_cvs_full_history.user_id;";
$dbh->do($sql);

# Now update the rows with weekly accounting. Since
# MySQL does not support UPDATE from a table we have
# to do it in a programmatic way and update one row 
# at a time.

print "Updating cvs_commits_wk and cvs_adds_wk in group_cvs_history...\n" if $verbose;
my $nb_of_days_back=7;
my $time_marker = strftime("%Y%m%d", gmtime( time() - $nb_of_days_back*86400 ));

$sql = "SELECT group_cvs_full_history.group_id,
                          user.user_name,
                          SUM(cvs_commits) AS commits_wk,
                          SUM(cvs_adds) AS adds_wk 
              FROM group_cvs_full_history,user_group,user
              WHERE user_group.user_id=group_cvs_full_history.user_id AND
                         group_cvs_full_history.group_id=user_group.group_id AND
                         user.user_id=group_cvs_full_history.user_id AND
                         group_cvs_full_history.day >= $time_marker
              GROUP BY group_cvs_full_history.group_id, group_cvs_full_history.user_id";

$res = $dbh->prepare($sql);
$res->execute();

my $sql_upd;
while(my ($group_id, $user_name, $cvs_commits_wk, $cvs_adds_wk) = $res->fetchrow()) {
  $sql_upd = "UPDATE group_cvs_history
                        SET cvs_commits_wk=$cvs_commits_wk, cvs_adds_wk=$cvs_adds_wk
                        WHERE group_id=$group_id AND user_name='$user_name'";
  $dbh->do($sql_upd);
}

print " done.\n" if $verbose;

##
## EOF
##

