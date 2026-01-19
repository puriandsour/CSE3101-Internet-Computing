<?php
/**
 * 403 Access Denied Page - Updated Design
 */
?>
<!-- Load Tailwind for this specific high-fidelity page -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="flex flex-col items-center justify-center py-12 bg-[#f9f9fb] min-h-[600px] rounded-xl shadow-sm border border-[#e9ebf2]">
    <div class="layout-content-container flex flex-col max-w-[960px] w-full items-center">
        <h1 class="text-[#0f121a] tracking-light text-[32px] font-bold leading-tight px-4 text-center pb-3 pt-6">403 - Access Denied</h1>
        
        <div class="flex w-full @container p-4 justify-center">
            <div class="max-w-[600px] w-full gap-1 overflow-hidden bg-[#f9f9fb] aspect-[3/2] rounded-lg shadow-sm border border-[#e9ebf2] flex">
                <div class="w-full bg-center bg-no-repeat bg-cover aspect-auto rounded-none flex-1" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAnHzLaeUqupQ0YXMTUPmzw96URhtFJ2Py9thdwQvhFy0pdc6zn1b1YLmJA_KbipMaKSfYElZFZclt0XLIjNkYlEaD6tht0V9YMQ0OTzEK0QJvb-E89ZsZBdalQ0NEHyTASLO1gE9B7R2m4XOl6Pye3ZG9vXSHTBjIqtcr1vyETZQXdVj401wW4JZ7p8GO-FuHqRMr-wRsRNeRlgrYTtVHpfl0ExjDXLSC-EIJV0fPjfJAYBEfGgHaKaXeIBwEShcJTmrX6vL9VvFY");'></div>
            </div>
        </div>

        <p class="text-[#0f121a] text-base font-normal leading-normal pb-3 pt-1 px-4 text-center max-w-[600px]">
            You do not have permission to access this page. Please contact your administrator for assistance.
        </p>
        
        <div class="flex px-4 py-3 justify-center gap-4 flex-col items-center">
            <a href="index.php" class="flex min-w-[200px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-[#1e3b8a] text-[#f9f9fb] text-sm font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                <span class="truncate">Back to Dashboard</span>
            </a>
            <a href="index.php?controller=help" class="text-[#556591] text-sm font-normal leading-normal underline hover:text-[#1e3b8a]">Help and Docs</a>
        </div>
    </div>
</div>