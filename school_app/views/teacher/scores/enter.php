<?php
/**
 * Enter Scores View - High Fidelity Single Student Entry
 * Data provided by ScoreController: $classes, $terms, $subjects, $selectedClassId, $selectedTermId, $selectedSubjectId
 */

$classId = $selectedClassId ?? '';
$termId = $selectedTermId ?? '';
$subjectId = $selectedSubjectId ?? '';

// Find names for the header
$className = 'Select Class';
$subjName = 'Select Subject';

foreach ($classes as $c) {
    if ($c->id == $classId) {
        $className = $c->grade_name . ' - ' . $c->name;
        break;
    }
}
foreach ($subjects as $s) {
    if ($s->id == $subjectId) {
        $subjName = $s->name;
        break;
    }
}
?>

<!-- Load Tailwind -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-white">
    <div class="flex flex-col gap-6 p-10 max-w-[1200px]">

        <!-- Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0f121a] tracking-light text-[32px] font-bold leading-tight">Enter Student Score</h1>
            <div class="flex flex-col mt-2">
                <p class="text-[#0f121a] text-lg font-bold" id="formHeaderDisplay">
                    <?php echo htmlspecialchars($className); ?> | <?php echo htmlspecialchars($subjName); ?>
                </p>
                <p class="text-[#556591] text-sm font-medium">Student Score Entry</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 mt-4">
            <!-- Form Section -->
            <form method="POST" action="index.php?controller=score&action=save" class="flex-1 flex flex-col gap-6"
                id="scoreForm">
                <input type="hidden" name="scores[0][enrollment_id]" id="enrollmentIdInput">

                <!-- Grade / Class -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Grade</label>
                    <div class="relative">
                        <select name="class_id" id="classSelect" required onchange="onClassChange()"
                            class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium focus:ring-[#1145d4] focus:border-[#1145d4]">
                            <option value="">Select Grade</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class->id; ?>" <?php echo $class->id == $classId ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Subject -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Subject</label>
                    <div class="relative">
                        <select name="subject_id" id="subjectSelect" required onchange="onFilterChange()"
                            class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium focus:ring-[#1145d4] focus:border-[#1145d4]">
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject->id; ?>" <?php echo $subject->id == $subjectId ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($subject->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Term -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Term</label>
                    <div class="relative">
                        <select name="term_id" id="termSelect" required onchange="onFilterChange()"
                            class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium focus:ring-[#1145d4] focus:border-[#1145d4]">
                            <option value="">Select Term</option>
                            <?php foreach ($terms as $term): ?>
                                <option value="<?php echo $term->id; ?>" <?php echo $term->id == $termId ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($term->name . ' - ' . ($term->school_year_name ?? '')); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Student Name -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Student Name</label>
                    <div class="relative">
                        <select id="studentSelect" required onchange="onStudentSelect()"
                            class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium focus:ring-[#1145d4] focus:border-[#1145d4]">
                            <option value="">Select Student</option>
                            <!-- Populated via AJAX -->
                        </select>
                    </div>
                </div>

                <!-- Score -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Score (0-100)</label>
                    <input type="number" name="scores[0][score]" id="scoreInput" min="0" max="100"
                        placeholder="Enter Score" required
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium placeholder:text-[#556591] focus:ring-[#1145d4] focus:border-[#1145d4]">
                </div>

                <!-- Remarks -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#0f121a] text-base font-bold">Remarks</label>
                    <textarea name="scores[0][remarks]" id="remarksInput" placeholder="Enter Remarks" rows="4"
                        class="w-full p-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0f121a] text-base font-medium placeholder:text-[#556591] focus:ring-[#1145d4] focus:border-[#1145d4]"></textarea>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="flex min-w-[140px] h-12 px-6 items-center justify-center rounded-xl bg-[#1145d4] text-white text-base font-bold hover:bg-[#0e39af] transition-colors shadow-sm active:scale-95">
                        Save Score
                    </button>
                </div>
            </form>

            <!-- Illustration Section -->
            <div class="hidden lg:flex flex-1 justify-center items-start pt-10">
                <div class="w-full max-w-[400px] h-auto aspect-[4/3] bg-center bg-no-repeat bg-cover rounded-2xl shadow-lg border border-[#e9ebf2]"
                    style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC4KuUR2KlT8GwvrMMKR5sXPaf1jnvN228UNGIS_IL3kqcpqW6nTuAZMMaID_ggJowCveHqBjrcHcxhR0adYoLyNKQbkMzRWCAKd6C8y2wy1tYATE-nvfYhF0dHEbvzblPI8pPPq2ZqE9sz6BIyh_CNES3Zr0bT0FZHz6P5TvLRroaDEfHG1dVxzRvk3YrOO1u19RS02yPJIkG-tz2LZwXVUKUrJmTXXtO8RkeevJZUh_IzhbqSH-Jk-Q2qDb6_zVakHHfTdH_D_LM');">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let studentsData = [];

    function onClassChange() {
        const classId = document.getElementById('classSelect').value;
        updateHeader();

        // Fetch subjects for this class
        if (classId) {
            fetch(`index.php?controller=score&action=getSubjects&class_id=${classId}`)
                .then(res => res.json())
                .then(data => {
                    const subSelect = document.getElementById('subjectSelect');
                    subSelect.innerHTML = '<option value="">Select Subject</option>';
                    if (data.subjects) {
                        data.subjects.forEach(s => {
                            subSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                        });
                    }
                    onFilterChange();
                });
        } else {
            document.getElementById('subjectSelect').innerHTML = '<option value="">Select Subject</option>';
            onFilterChange();
        }
    }

    function onFilterChange() {
        updateHeader();
        const classId = document.getElementById('classSelect').value;
        const termId = document.getElementById('termSelect').value;
        const subjectId = document.getElementById('subjectSelect').value;

        const stuSelect = document.getElementById('studentSelect');
        stuSelect.innerHTML = '<option value="">Select Student</option>';

        if (classId && termId && subjectId) {
            fetch(`index.php?controller=score&action=getStudents&class_id=${classId}&term_id=${termId}&subject_id=${subjectId}`)
                .then(res => res.json())
                .then(data => {
                    studentsData = data.students || [];
                    studentsData.forEach(s => {
                        stuSelect.innerHTML += `<option value="${s.id}">${s.first_name} ${s.last_name}</option>`;
                    });
                });
        }
    }

    function onStudentSelect() {
        const studentId = document.getElementById('studentSelect').value;
        const student = studentsData.find(s => s.id == studentId);

        const scoreInput = document.getElementById('scoreInput');
        const remarksInput = document.getElementById('remarksInput');
        const enrollmentIdInput = document.getElementById('enrollmentIdInput');

        if (student) {
            scoreInput.value = student.current_score || '';
            remarksInput.value = student.remarks || '';
            enrollmentIdInput.value = student.enrollment_id;
        } else {
            scoreInput.value = '';
            remarksInput.value = '';
            enrollmentIdInput.value = '';
        }
    }

    function updateHeader() {
        const classText = document.getElementById('classSelect').options[document.getElementById('classSelect').selectedIndex].text;
        const subjText = document.getElementById('subjectSelect').options[document.getElementById('subjectSelect').selectedIndex].text;

        const display = (classText === 'Select Grade' ? 'Select Class' : classText) + ' | ' +
            (subjText === 'Select Subject' ? 'Select Subject' : subjText);

        document.getElementById('formHeaderDisplay').innerText = display;
    }

    // Initial load if pre-selected
    window.onload = function () {
        const classId = '<?php echo $classId; ?>';
        if (classId) {
            onFilterChange();
        }
    };
</script>