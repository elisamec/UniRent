function updateCheckboxValues()
{
 var smokerCheckbox = document.getElementById('smoker');
 var hiddenSmokerInput = document.getElementById('hiddenSmoker');
 hiddenSmokerInput.value = smokerCheckbox.checked ? true : false;

 var animalsCheckbox = document.getElementById('animals');
 var hiddenAnimalsInput = document.getElementById('hiddenAnimals');
 hiddenAnimalsInput.value = animalsCheckbox.checked ? true : false;
}