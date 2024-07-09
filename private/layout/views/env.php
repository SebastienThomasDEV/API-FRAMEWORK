<div class="w-full p-6">
    <div class=" p-8 shadow-lg rounded-lg border border-gray-400">
        <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Configuration de l'application</h1>
        <hr class="my-4 border-gray-400"/>
        <form class="flex flex-col gap-3" method="post" action="/env">
            <?php
            foreach ($env as $key => $value) {
                ?>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 mb-2"> <?= $key ?></span>
                    <input
                            type="text"
                            placeholder="<?= $key ?>"
                            value="<?= $value ?>"
                            name="<?= $key ?>"
                            class="
                                w-full
                                rounded
                                py-3
                                px-[14px]
                                text-body-color text-base
                                border border-[f0f0f0]
                                outline-none
                                focus-visible:shadow-none
                                focus:border-primary
                                "
                    />
                </label>
                <?php
            }
            ?>
            <div class="flex items-center gap-3 text-indigo-600 cursor-pointer">
                + Ajouter une nouvelle variable</div>
            <hr class="my-4 border-gray-400"/>
            <button
                    type="submit"
                    class="
                        w-fit
                        bg-purple-600
                        text-white
                        py-2
                        px-10
                        rounded
                        font-semibold
                        text-sm
                        hover:bg-primary-dark
                        transition
                        duration-200
                        ease-in-out
                        "
            >
                Enregistrer
            </button>
        </form>
    </div>
</div>


