<div class="text-white w-full flex flex-col rounded-xl shadow-lg p-4">

    <?php
        foreach ($lines as $line) {
            if ($line !== "") echo "<div class='text-gray-500 font-bold text-sm'># $line</div>";
        }
    ?>
</div>