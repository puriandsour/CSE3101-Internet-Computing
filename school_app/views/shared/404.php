<?php
/**
 * 404 Not Found Page
 */
?>
<!-- Load Tailwind for this specific high-fidelity page -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="flex flex-col items-center justify-center py-12">
    <div class="layout-content-container flex flex-col max-w-[960px] w-full">
        <div class="w-full" style="height: 40px;"></div>
        <h1 class="text-[#111814] tracking-light text-[64px] font-bold leading-tight px-4 text-center pb-3">404</h1>
        <p class="text-[#111814] text-xl font-medium leading-normal pb-3 px-4 text-center">Oops! The page you are
            looking for has moved or doesn't exist.</p>

        <div class="flex px-4 py-6 justify-center">
            <a href="index.php"
                class="flex min-w-[180px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-[#10a24a] text-white text-sm font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                <span class="truncate">Back to Dashboard</span>
            </a>
        </div>

        <a href="index.php?controller=help"
            class="text-[#638872] text-sm font-normal leading-normal pb-8 px-4 text-center underline block hover:text-[#10a24a]">Help
            and Docs</a>

        <div class="flex w-full grow bg-white @container p-4 justify-center">
            <div class="max-w-[600px] w-full gap-1 overflow-hidden bg-white aspect-[3/2] rounded-2xl shadow-sm border border-[#f0f4f2] flex"
                style="min-height: 400px;">
                <div class="w-full bg-center bg-no-repeat bg-cover aspect-auto rounded-none flex-1"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAlLsJEle4At7VOdP2Ru0eYzx8qw-2LggjL_sNl-75FZmz6sRv2-rXBtHzWFwykqq8fjtW1IKogYNej7hFadKWE3LGxSNZDD4WNgj00gKC7C0AGNVKztlrhoirJLqgM6l4gCea5f7w8gDbCMr5j_t26SaUmCX8y4X0re3HAMKCIVRGDHJlh2xaEEX0h4U3PsPvCQFg24v6mtrQdZZUNKUfp3DFvUeAUim3oDxjMmzxGD_G6roCz6CidOO18a55sP3i55X-ET1-ipno");'>
                </div>
            </div>
        </div>
    </div>
</div>