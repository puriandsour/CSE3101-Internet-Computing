<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1 bg-white">
    <div class="flex flex-col gap-1 mb-6">
        <div class="flex items-center gap-2 text-[#4e6797] text-sm font-medium">
            <a href="index.php?controller=report&action=index" class="hover:text-[#195de6]">Reports</a>
            <span>/</span>
        </div>
        <h1 class="text-[#0e121b] tracking-light text-[32px] font-bold leading-tight">Reports</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 max-w-[1000px]">
        <!-- Individual Student Report Card -->
        <div
            class="flex flex-col md:flex-row items-stretch border border-[#e7ebf3] rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="w-full md:w-[320px] bg-center bg-no-repeat bg-cover aspect-video md:aspect-auto"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBaxGY6zq5ooE-ZjADfiY43PXNT_JlBudS_CHnkH-Ak4qx9bC3JDQWMleNexW1mvPZWoiwGj2V0aiF_Y9-Mo1XQr5U43F6CNCelPRTVqYV3tUA5WHRXvix2vE5WVb3JrgWfeVZYbDBsLsJPF9nOH_IMuPizhPYVlk7mx81_jS1PUqlFAbmIrUiDtomEahEFIAFZsb3HavIo2Fdm7RnmMstu1aMbaRgD4lPJ_xpy2FUFBV_Ee0WX_EhDH8MTNCUmeb438id7GIXay0A");'>
            </div>
            <div class="flex-1 p-8 flex flex-col justify-center gap-3">
                <h2 class="text-[#0e121b] text-xl font-bold">Individual Student Report Card</h2>
                <p class="text-[#4e6797] text-base leading-relaxed">
                    Generate a detailed report card for a specific student, including scores for each subject in a
                    selected term.
                </p>
                <div class="flex justify-end mt-4">
                    <a href="index.php?controller=report&action=student"
                        class="px-6 py-2.5 rounded-lg bg-[#1e3b8a] text-white text-sm font-bold shadow-sm hover:bg-[#152e6d] transition-colors">
                        Select Student
                    </a>
                </div>
            </div>
        </div>

        <!-- Grade Performance Analytics -->
        <div
            class="flex flex-col md:flex-row items-stretch border border-[#e7ebf3] rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="w-full md:w-[320px] bg-center bg-no-repeat bg-cover aspect-video md:aspect-auto"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAJsK5JqOvSxCTJg_O6LV8_CukHe_4bNL86_ZaA48ppz4HrqVDVN8DKtXBp0xVU8y5PACtz5gs5EjDCm3WOnOc7SEvJ-6XNWiuTjbhm7DZ8r6b9HosGWjgsy615HYME8SnIM0hA4JWa67y276tsgBA6CVN_S0xyZtUsaWHtQlvT5tlwWnnKHzt6mnNmj4addHXNXXGqNACXgFk5JihGVn8scx5WOHl9HOvY85s1jGrWFkeXyK_PqxlCC0Of8SlYZlxPw-TWh9rB5UA");'>
            </div>
            <div class="flex-1 p-8 flex flex-col justify-center gap-3">
                <h2 class="text-[#0e121b] text-xl font-bold">Grade Performance Analytics</h2>
                <p class="text-[#4e6797] text-base leading-relaxed">
                    View average scores and distribution charts across subjects for a specific grade, providing insights
                    into overall class performance.
                </p>
                <div class="flex justify-end mt-4">
                    <a href="index.php?controller=report&action=performance"
                        class="px-6 py-2.5 rounded-lg bg-[#1e3b8a] text-white text-sm font-bold shadow-sm hover:bg-[#152e6d] transition-colors">
                        View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>