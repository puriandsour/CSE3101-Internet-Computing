<?php
/**
 * Teacher Dashboard View - High Fidelity Redesign
 * Data provided by TeacherController: 
 * - $currentYear, $currentTerm, $myClassesCount, $myStudentsCount, $classes, $recentScores
 */

$yearName = $currentYear->name ?? 'N/A';
$termName = $currentTerm->name ?? 'N/A';

// Helper function for relative time
function time_elapsed_string($datetime, $full = false)
{
    if (!$datetime)
        return 'N/A';
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<!-- Load Tailwind for this specific high-fidelity page -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-[#f9f9fb]">
    <div class="flex flex-col gap-6 p-6 max-w-[1200px]">

        <!-- Header Section -->
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0f121a] tracking-light text-[32px] font-bold leading-tight">Teacher Dashboard</h1>
            <p class="text-[#556591] text-base font-medium">
                Current School Year: <span class="text-[#0f121a]"><?php echo htmlspecialchars($yearName); ?></span>,
                Term: <span class="text-[#0f121a]"><?php echo htmlspecialchars($termName); ?></span>
            </p>
        </div>

        <!-- Summary Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-2 bg-white p-6 rounded-xl border border-[#e9ebf2] shadow-sm">
                <p class="text-[#556591] text-sm font-medium uppercase tracking-wider">Total Students</p>
                <p class="text-[#0f121a] text-[40px] font-bold leading-tight">
                    <?php echo number_format($myStudentsCount); ?></p>
            </div>
            <div class="flex flex-col gap-2 bg-white p-6 rounded-xl border border-[#e9ebf2] shadow-sm">
                <p class="text-[#556591] text-sm font-medium uppercase tracking-wider">Total Classes</p>
                <p class="text-[#0f121a] text-[40px] font-bold leading-tight"><?php echo $myClassesCount; ?></p>
            </div>
        </div>

        <!-- Classes Section -->
        <div class="flex flex-col gap-4 mt-4">
            <h2 class="text-[#0f121a] text-2xl font-bold leading-tight">Classes</h2>
            <div class="overflow-hidden bg-white border border-[#e9ebf2] rounded-xl shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#fcfcfd] border-bottom border-[#e9ebf2]">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-[#556591] uppercase tracking-wider">Class</th>
                            <th class="px-6 py-4 text-xs font-bold text-[#556591] uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-xs font-bold text-[#556591] uppercase tracking-wider text-right">
                                Students</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e9ebf2]">
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <tr class="hover:bg-[#f9f9fb] transition-colors cursor-pointer"
                                    onclick="window.location.href='index.php?controller=teacher&action=viewClass&id=<?php echo $class->id; ?>'">
                                    <td class="px-6 py-5 text-sm font-bold text-[#0f121a]">
                                        <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-medium text-[#1e3b8a]">
                                        <?php echo htmlspecialchars($class->primary_subject); ?>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-medium text-[#556591] text-right">
                                        <?php echo $class->student_count ?? 0; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-[#556591] italic">No classes assigned.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Score Entries Section -->
        <div class="flex flex-col gap-6 mt-6">
            <h2 class="text-[#0f121a] text-2xl font-bold leading-tight">Recent Score Entries</h2>

            <?php if (!empty($recentScores)): ?>
                <div class="relative flex flex-col gap-8 pl-10">
                    <!-- Vertical Line Connector -->
                    <div class="absolute left-[15px] top-6 bottom-6 w-[2px] bg-[#e9ebf2]"></div>

                    <?php foreach ($recentScores as $score): ?>
                        <div class="relative flex flex-col gap-1">
                            <!-- Icon/Dot -->
                            <div
                                class="absolute -left-[35px] top-0 flex items-center justify-center w-8 h-8 rounded-full bg-white border-2 border-[#1e3b8a] z-10 text-[#1e3b8a]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z">
                                    </path>
                                </svg>
                            </div>

                            <p class="text-[#0f121a] text-base font-bold">
                                New score entered for
                                <?php echo htmlspecialchars($score->first_name . ' ' . $score->last_name); ?>
                                <span class="text-[#556591] font-medium ml-2">•
                                    <?php echo htmlspecialchars($score->subject_name); ?></span>
                            </p>
                            <p class="text-[#556591] text-sm font-medium">
                                <?php echo time_elapsed_string($score->created_at); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="p-8 text-center bg-white rounded-xl border border-dashed border-[#d1d5db]">
                    <p class="text-[#556591] font-medium">No recent score entries found.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Static Action Button (matching image) -->
        <div class="flex justify-end mt-8 pb-10">
            <a href="index.php?controller=score&action=enter"
                class="flex min-w-[140px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-6 bg-[#1e3b8a] text-white text-base font-bold leading-normal tracking-[0.015em] transition-all hover:bg-[#152e6d] hover:shadow-lg active:scale-95">
                <span class="truncate">Enter Scores</span>
            </a>
        </div>
    </div>
</div>