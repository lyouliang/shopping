import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import axios from 'axios';
import { ref, reactive } from 'vue';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function useCart() {

}