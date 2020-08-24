This lifecycle trigger holds an internal archiving status table and works with 1 step :

the trigger fires when it detects that a course has been successfully archived in the associated
archive remote Moodle, i.e, has been tranfered to this remote archive backend. Note that the archive
backend is a .mbz file consumer, anyway it consumes it, (store it, retore it, or anything else), pursuant
it sends an ackowledge service call to mark it has been consumed.

Triggering the markforarchive step :
Any other trigger can trigger the markforarchive step, which will mark the trigger and eligible courses
as ready for archive (state 1).

the remote archiving endpoint will get the backup file and consumes it, then calls the archived acknoledgment
service to mark it done (state 2).

The current trigger triggers at this final point, getting courses for which the archive status is "done".

A final "resetarchivestate" step MUST be present in the step stack after the trigger invocation to ensure
the trigger is not triggered again an again.