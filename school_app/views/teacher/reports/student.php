<?php
/**
 * Student Selection View for Reports - Matching Image 2
 * Data provided: $classes, $terms
 */
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-white">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-[#4e6797] text-sm font-medium mb-2">
        <a href="index.php?controller=report&action=index" class="hover:text-[#195de6]">Reports</a>
        <span>/</span>
        <span class="text-[#0e121b]">Report Card</span>
    </div>

    <div class="flex flex-col gap-1 mb-8">
        <h1 class="text-[#0e121b] tracking-light text-[32px] font-bold leading-tight">Generate Report Card</h1>
        <p class="text-[#4e6797] text-base">Configure report settings for the selected student</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-12 max-w-[1100px]">
        <!-- Selection Form -->
        <div class="flex-1 flex flex-col gap-8">
            <!-- Class Selection -->
            <div class="flex flex-col gap-3">
                <label class="text-[#0e121b] text-base font-bold">Class</label>
                <div class="relative">
                    <select id="classSelect" onchange="onClassChange()"
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0e121b] text-base font-medium appearance-none focus:ring-[#195de6] focus:border-[#195de6]">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>">
                                <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#4e6797]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Student Selection -->
            <div class="flex flex-col gap-3">
                <label class="text-[#0e121b] text-base font-bold">Student</label>
                <div class="relative">
                    <select id="studentSelect" onchange="onStudentSelect()"
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0e121b] text-base font-medium appearance-none focus:ring-[#195de6] focus:border-[#195de6]">
                        <option value="">Select Student</option>
                        <!-- Populated via AJAX -->
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#4e6797]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Student Detail Card (Shown when selected) -->
            <div id="studentDetail" class="hidden flex flex-col gap-1 p-6 bg-[#f8f9fc] rounded-2xl border border-[#e7ebf3]">
                <p class="text-[#0e121b] text-lg font-bold" id="detailName"></p>
                <p class="text-[#4e6797] text-sm" id="detailSub"></p>
            </div>

            <!-- Term Selection -->
            <div class="flex flex-col gap-3">
                <label class="text-[#0e121b] text-base font-bold">Select Term</label>
                <div class="relative">
                    <select id="termSelect" onchange="onTermChange()"
                        class="w-full h-14 px-4 bg-[#f9f9fb] border-[#e9ebf2] rounded-xl text-[#0e121b] text-base font-medium appearance-none focus:ring-[#195de6] focus:border-[#195de6]">
                        <option value="">Select Term</option>
                        <?php foreach ($terms as $term): ?>
                            <option value="<?php echo $term->id; ?>">
                                <?php echo htmlspecialchars($term->name . ' - ' . ($term->school_year_name ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#4e6797]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="flex justify-end mt-4">
                <button onclick="generateReport()"
                    class="px-10 py-3 rounded-xl bg-[#195de6] text-white text-base font-bold shadow-lg hover:bg-[#1145d4] transition-all active:scale-95">
                    Generate Report
                </button>
            </div>
        </div>

        <!-- Illustration -->
        <div class="hidden lg:flex flex-1 justify-center items-start pt-10">
            <div class="w-full max-w-[400px] h-[300px] bg-center bg-no-repeat bg-cover rounded-3xl shadow-sm"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBEj5ehZ4nCa5NMZqnoJbjvmYAcUKmviHi_sNvgA2C78sgN8OzTFGVwuKlel4DoxzIC3bAJL2BjhqE2H2au4Q8G6Ok-0kimLg5WMotcbrUS8XoTkv-glVoReBimvvtXAiAQLJ8AytprgeTHes9HnFRQxnJkpJZGaVYKoznEnGIYcCmH3j5_nC2QRokHxgHftmMIF1PbTp3RpJMI9hz_3ivF3o-ro6G8IYMpKciHQ_Q9YswuSQkRlt9E2Gs0gvCFFyH9jKDvL1mlswk");'>
            </div>
        </div>
    </div>
</div>

<script>
let studentsData = [];

function onClassChange() {
    loadStudents();
}

function onTermChange() {
    loadStudents();
}

function loadStudents() {
    const classId = document.getElementById('classSelect').value;
    const termId = document.getElementById('termSelect').value;
    const stuSelect = document.getElementById('studentSelect');
    
    // Don't clear if only term changed and class is empty
    if (!classId) {
        stuSelect.innerHTML = '<option value="">Select Student</option>';
        document.getElementById('studentDetail').classList.add('hidden');
        return;
    }

    // Keep current selection if possible
    const currentStuId = stuSelect.value;
    
    fetch(`index.php?controller=teacher&action=getClassStudents&class_id=${classId}&term_id=${termId}`)
        .then(res => res.json())
        .then(data => {
            studentsData = data.students || [];
            stuSelect.innerHTML = '<option value="">Select Student</option>';
            studentsData.forEach(s => {
                stuSelect.innerHTML += `<option value="${s.id}" ${s.id == currentStuId ? 'selected' : ''}>${s.first_name} ${s.last_name}</option>`;
            });
            onStudentSelect();
        });
}

function onStudentSelect() {
    const studentId = document.getElementById('studentSelect').value;
    const student = studentsData.find(s => s.id == studentId);
    const detail = document.getElementById('studentDetail');
    
    if (student) {
        document.getElementById('detailName').innerText = `${student.first_name} ${student.last_name}`;
        document.getElementById('detailSub').innerText = `Admission No: ${student.admission_no} | Class: ${document.getElementById('classSelect').options[document.getElementById('classSelect').selectedIndex].text}`;
        detail.classList.remove('hidden');
    } else {
        detail.classList.add('hidden');
    }
}

function generateReport() {
    const studentId = document.getElementById('studentSelect').value;
    const termId = document.getElementById('termSelect').value;
    
    if (!studentId || !termId) {
        alert("Please select both a student and a term.");
        return;
    }
    
    window.location.href = `index.php?controller=report&action=view&student_id=${studentId}&term_id=${termId}`;
}
</script>