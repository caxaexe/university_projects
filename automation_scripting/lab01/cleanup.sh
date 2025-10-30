#!/bin/bash

if [ $# -lt 1 ]; then
    echo "Использование: $0 <директория> [расширение1] [расширение2] ..."
    exit 1
fi

DIR=$1
shift

if [ $# -eq 0 ]; then
    EXTENSIONS=(".tmp")
else
    EXTENSIONS=("$@")
fi

if [ ! -d "$DIR" ]; then
    echo "Ошибка: директория '$DIR' не существует."
    exit 1
fi

deleted_count=0

for ext in "${EXTENSIONS[@]}"; do
    files=("$DIR"/*"$ext")
    
    for file in "${files[@]}"; do
        if [ -f "$file" ]; then
            rm "$file"
            ((deleted_count++))
        fi
    done
done

echo "Удалено файлов: $deleted_count"
