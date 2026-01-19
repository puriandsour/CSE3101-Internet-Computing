<?php
/**
 * Teacher Classes View - High Fidelity
 * Data provided by TeacherController: $classes
 */

// Define some variety for images to match the design feel
$classImages = [
    "https://lh3.googleusercontent.com/aida-public/AB6AXuC4KuUR2KlT8GwvrMMKR5sXPaf1jnvN228UNGIS_IL3kqcpqW6nTuAZMMaID_ggJowCveHqBjrcHcxhR0adYoLyNKQbkMzRWCAKd6C8y2wy1tYATE-nvfYhF0dHEbvzblPI8pPPq2ZqE9sz6BIyh_CNES3Zr0bT0FZHz6P5TvLRroaDEfHG1dVxzRvk3YrOO1u19RS02yPJIkG-tz2LZwXVUKUrJmTXXtO8RkeevJZUh_IzhbqSH-Jk-Q2qDb6_zVakHHfTdH_D_LM",
    "https://lh3.googleusercontent.com/aida-public/AB6AXuDgXcgy-ZmOHAkfC2dNkY_K1fv0j9LMT_tqV8Sqa2OEPX7C5-p74j6fGqXopR5Dl2zVqBm4j3RHG_VjlA9eaM71zZjjcf9Ez-YY6WsQJdyjReXACAUbanAkJ5NSBk2oTbmFFRjwgKCRNg63SGDTJ5vgHY5lsHkTBvxueUM-LIs8wdQVhOCPuljt-HhsBuNVtXme8KORRmXZxSmtDxteDM0A4Gxo38XG9Ihv_WArpgzNm4FNkFFyJ9mWsaf8Ol0lKTo8hiAUfJRBfzs",
    "https://lh3.googleusercontent.com/aida-public/AB6AXuAGigtYA4-9CKgZ-Z_NVEsVoU3RM8993lYwZ-gD3x6G0TSOijH9Fwf5PxNWdKazIOfR5yJ0O2o9FkJ6D8Bterl6voxmupAKBBPmUsfshU2P9UgD_TfptCkmqs9Wb65jzBfXLsxu9disgLsBFR6kT1dguOCZstjdc8FNRcff-e1RuNKRlkQ5Tzyi8l7-IWDybujazNeopIjXkYo9MsxYMblgmJGDoCm3xlYNqGxV2eOxmhCSlKx-YZO4Yqn4w71o_nl8XpZ1l2272a0"
];
?>

<!-- Load Tailwind for this specific high-fidelity page -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="layout-content-container flex flex-col w-full flex-1">
    <div class="flex flex-wrap justify-between gap-3 p-4">
        <p class="text-[#111318] tracking-light text-[32px] font-bold leading-tight min-w-72">My Classes</p>
    </div>

    <?php if (!empty($classes)): ?>
        <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-6 p-4">
            <?php foreach ($classes as $index => $class):
                $imgUrl = $classImages[$index % count($classImages)];
                ?>
                <div class="flex flex-col gap-3 pb-3 group cursor-pointer"
                    onclick="window.location.href='index.php?controller=teacher&action=viewClass&id=<?php echo $class->id; ?>'">
                    <div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-xl shadow-sm transition-transform group-hover:scale-[1.02]"
                        style='background-image: url("<?php echo $imgUrl; ?>");'></div>
                    <div>
                        <p class="text-[#111318] text-lg font-bold leading-normal">
                            <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                        </p>
                        <p class="text-[#616c89] text-sm font-medium leading-normal">
                            <?php echo htmlspecialchars($class->primary_subject); ?>
                        </p>
                        <p class="text-[#616c89] text-sm font-normal leading-normal italic">
                            <?php echo $class->student_count ?? 0; ?> Enrolled Students
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


    <?php else: ?>
        <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-[#d1d5db] m-4">
            <div class="text-[#9ca3af] mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 14h.01M9 17h.01M9 14h.01M12 17h.01M12 14h.01M15 17h.01M15 14h.01M12 11h.01M12 7h.01M15 11h.01M15 7h.01" />
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No classes assigned</h3>
            <p class="mt-1 text-sm text-gray-500">You don't have any classes assigned to you for the current term.</p>
        </div>
    <?php endif; ?>
</div>