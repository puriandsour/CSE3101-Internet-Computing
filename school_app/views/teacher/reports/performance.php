<?php
/**
 * Grade Performance Analytics View - Matching Image 3
 * Data provided: $classes, $terms, $selectedClassId, $selectedTermId, $subjects, $overallAverage
 */
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-white">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-[#4e6797] text-sm font-medium mb-2">
        <a href="index.php?controller=report&action=index" class="hover:text-[#195de6]">Reports</a>
        <span>/</span>
        <span class="text-[#0e121b]">Performance</span>
    </div>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-[#0e121b] tracking-light text-[32px] font-bold leading-tight">Grade Performance Report</h1>
        <button onclick="window.print()"
            class="px-5 py-2 rounded-lg bg-[#e7ebf3] text-[#0e121b] text-sm font-bold shadow-sm hover:bg-[#dce1eb] transition-colors">
            Print Report
        </button>
    </div>

    <div class="flex flex-col gap-10 max-w-[1000px]">
        <!-- Filters -->
        <form action="index.php" method="GET"
            class="flex flex-col gap-6 p-8 bg-white border border-[#e7ebf3] rounded-2xl">
            <input type="hidden" name="controller" value="report">
            <input type="hidden" name="action" value="performance">

            <h2 class="text-[#0e121b] text-lg font-bold">Filters</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0e121b] text-sm font-bold">Grade</label>
                    <select name="class_id" required onchange="this.form.submit()"
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0e121b] text-base font-medium">
                        <option value="">Select Grade</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>" <?php echo $class->id == $selectedClassId ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Term -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0e121b] text-sm font-bold">Term</label>
                    <select name="term_id" required onchange="this.form.submit()"
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0e121b] text-base font-medium">
                        <option value="">Select Term</option>
                        <?php foreach ($terms as $term): ?>
                            <option value="<?php echo $term->id; ?>" <?php echo $term->id == $selectedTermId ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($term->name . ' - ' . ($term->school_year_name ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- Stats & Chart -->
        <?php if ($selectedClassId && $selectedTermId): ?>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col gap-1">
                    <p class="text-[#0e121b] text-base font-medium">Average Scores by Subject</p>
                    <p class="text-[#0e121b] text-[40px] font-black leading-tight"><?php echo $overallAverage; ?></p>
                    <div class="flex items-center gap-1">
                        <span class="text-[#4e6797] text-sm font-medium">Average Score</span>
                        <span class="text-[#07883d] text-sm font-bold">+5%</span>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="flex items-end gap-x-12 min-h-[300px] px-8 pb-12 border-b border-[#f1f3f7] mt-4">
                    <?php if (!empty($subjects)): ?>
                        <?php foreach ($subjects as $s): ?>
                            <div class="flex-1 flex flex-col items-center gap-4 h-full justify-end">
                                <!-- Score Label -->
                                <span class="text-[#0e121b] text-sm font-bold"><?php echo round($s->avg_score, 1); ?></span>

                                <!-- Bar -->
                                <div class="w-16 bg-[#195de6] rounded-t-lg transition-all shadow-sm"
                                    style="height: <?php echo max(5, ($s->avg_score / 100) * 200); ?>px;">
                                </div>

                                <!-- Subject Label -->
                                <span class="text-[#4e6797] text-[13px] font-bold tracking-wider text-center max-w-[100px]">
                                    <?php echo htmlspecialchars($s->name); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="w-full flex items-center justify-center p-20 text-[#4e6797] italic">
                            No subject data found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="p-20 text-center text-[#4e6797] italic border-2 border-dashed border-[#e7ebf3] rounded-3xl">
                Please select a Grade and Term to view performance analytics.
            </div>
        <?php endif; ?>
    </div>
</div>