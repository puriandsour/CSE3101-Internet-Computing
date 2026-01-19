<?php
/**
 * Student Report Card View - Matching Image 1
 * Data provided: $student, $term, $enrollment, $scores, $average
 */
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-white">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-8 max-w-[1000px]">
        <a href="index.php?controller=report&action=student"
            class="px-6 py-2 rounded-lg bg-[#e7ebf3] text-[#0e121b] text-sm font-bold shadow-sm hover:bg-[#dce1eb] transition-colors">
            Back
        </a>
        <button onclick="window.print()"
            class="px-8 py-2 rounded-lg bg-[#195de6] text-white text-sm font-bold shadow-md hover:bg-[#1145d4] transition-all">
            Print
        </button>
    </div>

    <!-- Report Box -->
    <div
        class="max-w-[1000px] border border-[#e7ebf3] rounded-[32px] overflow-hidden shadow-sm bg-white print:border-none print:shadow-none">
        <!-- Banner -->
        <div class="w-full h-48 bg-[#fdf5e6] flex items-center justify-center overflow-hidden">
            <div class="w-full h-full bg-center bg-no-repeat bg-contain"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBEj5ehZ4nCa5NMZqnoJbjvmYAcUKmviHi_sNvgA2C78sgN8OzTFGVwuKlel4DoxzIC3bAJL2BjhqE2H2au4Q8G6Ok-0kimLg5WMotcbrUS8XoTkv-glVoReBimvvtXAiAQLJ8AytprgeTHes9HnFRQxnJkpJZGaVYKoznEnGIYcCmH3j5_nC2QRokHxgHftmMIF1PbTp3RpJMI9hz_3ivF3o-ro6G8IYMpKciHQ_Q9YswuSQkRlt9E2Gs0gvCFFyH9jKDvL1mlswk");'>
            </div>
        </div>

        <div class="p-12">
            <h2 class="text-[#0e121b] text-[28px] font-bold text-center mb-12 uppercase tracking-wide">STUDENT REPORT
                CARD</h2>

            <!-- Info Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                <!-- Left: Student -->
                <div class="flex flex-col gap-6">
                    <h3 class="text-[#0e121b] text-base font-bold pb-2 border-b border-[#e7ebf3]">Student Information
                    </h3>
                    <div class="grid grid-cols-2 gap-y-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[#4e6797] text-xs font-bold uppercase">Student Name</span>
                            <span
                                class="text-[#0e121b] text-sm font-medium"><?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[#4e6797] text-xs font-bold uppercase">Student ID</span>
                            <span
                                class="text-[#0e121b] text-sm font-medium"><?php echo htmlspecialchars($student->admission_no); ?></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[#4e6797] text-xs font-bold uppercase">Class</span>
                            <span
                                class="text-[#0e121b] text-sm font-medium"><?php echo htmlspecialchars($enrollment->grade_name . ' - ' . $enrollment->class_name); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Period -->
                <div class="flex flex-col gap-6">
                    <h3 class="text-[#0e121b] text-base font-bold pb-2 border-b border-[#e7ebf3]">School Year & Term
                    </h3>
                    <div class="grid grid-cols-2 gap-y-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[#4e6797] text-xs font-bold uppercase">School Year</span>
                            <span
                                class="text-[#0e121b] text-sm font-medium"><?php echo htmlspecialchars($enrollment->year_name); ?></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[#4e6797] text-xs font-bold uppercase">Term</span>
                            <span
                                class="text-[#0e121b] text-sm font-medium"><?php echo htmlspecialchars($term->name); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Table -->
            <div class="mb-12">
                <h3 class="text-[#0e121b] text-base font-bold mb-6">Academic Performance</h3>
                <div class="overflow-hidden rounded-xl border border-[#e7ebf3]">
                    <table class="w-full text-left">
                        <thead class="bg-[#f8f9fc] text-[#0e121b] text-sm font-bold">
                            <tr>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4 text-center">Score (0-100)</th>
                                <th class="px-6 py-4">Teacher Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="text-[#0e121b] text-sm">
                            <?php foreach ($scores as $s): ?>
                                <tr class="border-t border-[#e7ebf3]">
                                    <td class="px-6 py-5 font-medium"><?php echo htmlspecialchars($s->subject_name); ?></td>
                                    <td class="px-6 py-5 text-center text-[#195de6] font-bold"><?php echo $s->score; ?></td>
                                    <td class="px-6 py-5 text-[#4e6797] italic">
                                        <?php echo htmlspecialchars($s->remarks ?: 'Good performance.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div>
                <h3 class="text-[#0e121b] text-base font-bold mb-6">Term Summary</h3>
                <div class="flex items-center justify-between border-t border-[#e7ebf3] py-8">
                    <span class="text-[#4e6797] text-sm font-bold uppercase">Term Average</span>
                    <span class="text-[#0e121b] text-lg font-black"><?php echo $average; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .layout-container {
            display: block !important;
        }

        .layout-content-container {
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        button,
        a {
            display: none !important;
        }

        .max-w-[1000px] {
            max-width: none !important;
            border: none !important;
        }

        body {
            background: white !important;
        }
    }
</style>