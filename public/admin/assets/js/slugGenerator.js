function slugGenerator(nameSelector, slugSelector) {
    // Slug generation logic
    const nameInput = document.querySelector(nameSelector);
    const slugInput = document.querySelector(slugSelector);

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            const slug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');

            slugInput.value = slug;
        });
    }
}