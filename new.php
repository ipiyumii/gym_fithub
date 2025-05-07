<?php
include("./config.php"); // ✅ Add this line
include("./includes/header.php");
?>

<?php include("./includes/header.php"); ?>
<!-- Room Schedule Reference -->
<div class="mt-8 bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Room Schedule</h2>
                    <p class="text-gray-600 mb-4">Use this reference to avoid scheduling conflicts. The system will prevent you from double-booking a room, but this can help you plan more effectively.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Monday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Tuesday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Wednesday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Thursday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Friday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Saturday</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sunday</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                // Get all rooms
                                $rooms = [];
                                $room_sql = "SELECT DISTINCT room FROM class_schedules ORDER BY room";
                                $room_result = mysqli_query($conn, $room_sql);
                                if($room_result) {
                                    while($room_row = mysqli_fetch_assoc($room_result)) {
                                        $rooms[] = $room_row['room'];
                                    }
                                }
                                
                                // For each room, get the schedule for each day
                                foreach($rooms as $current_room):
                                ?>
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($current_room); ?></td>
                                    
                                    <?php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    foreach($days as $day):
                                        // Get schedules for this room and day
                                        $schedule_sql = "SELECT cs.start_time, cs.end_time, gc.name as class_name 
                                                        FROM class_schedules cs
                                                        JOIN gym_classes gc ON cs.class_id = gc.id
                                                        WHERE cs.room = ? AND cs.day_of_week = ? AND cs.status = 'active'
                                                        ORDER BY cs.start_time";
                                        $schedule_stmt = mysqli_prepare($conn, $schedule_sql);
                                        mysqli_stmt_bind_param($schedule_stmt, "ss", $current_room, $day);
                                        mysqli_stmt_execute($schedule_stmt);
                                        $schedule_result = mysqli_stmt_get_result($schedule_stmt);
                                        
                                        $day_schedules = [];
                                        while($schedule_row = mysqli_fetch_assoc($schedule_result)) {
                                            $day_schedules[] = $schedule_row;
                                        }
                                        mysqli_stmt_close($schedule_stmt);
                                    ?>
                                    <td class="px-4 py-2 text-xs text-gray-600">
                                        <?php if(empty($day_schedules)): ?>
                                        <span class="text-gray-400">No classes</span>
                                        <?php else: ?>
                                            <?php foreach($day_schedules as $schedule): ?>
                                            <div class="mb-1 p-1 rounded bg-blue-50">
                                                <div class="font-medium"><?php echo htmlspecialchars($schedule['class_name']); ?></div>
                                                <div>
                                                    <?php echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                                         date('h:i A', strtotime($schedule['end_time'])); ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if(empty($rooms)): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No room schedules found yet.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>