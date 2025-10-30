/*Пусть символ "#" это backspace (т.е. нажатие на клавишу удалить символ).
Дана строка содержащая буквы и знак "#", необходимо произвести обработку строки, учитывая символы "#".
• "abc#d##c" ==> "ас" (т.е. Вы написали а в с, потом одну букву стерли, потом написали д, стерли две буквы и написали с).
• "abc##d######" ==> ""  */ 
function processString(str) {
    const stack = [];
    for (const char of str){
        if (char === "#"){
            stack.pop();
        } else {
            stack.push(char);
        }
    }   
    return stack.join("");
}

console.log(processString("abc#d##c"));
console.log(processString("abc##d######"));

/*Дана строка. Ваша задача вернуть средний символ строки.
• Если длина строки нечетна, необходимо вернуть средний символ.
• Если длина строки четна, необходимо вернуть два средних символа. */
function getMiddle(str) {
    const half = Math.floor(str.length / 2);
    if (str.length % 2 == 0) {
        return str.slice(half - 1, half + 1);
    } else {
        return str[half]; 
    }
}

console.log(getMiddle("test"));
console.log(getMiddle("testing"));
