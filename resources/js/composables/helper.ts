export const truncateMessage = (message: string, maxLength = 30) => {
    return message.length > maxLength ? message.slice(0, maxLength) + '...' : message;
};
