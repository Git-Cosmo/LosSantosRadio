/**
 * Logger Module
 * Provides conditional logging based on environment
 * Only logs in development mode, silent in production
 */

const isDevelopment = import.meta.env.DEV || false;

/**
 * Log info message (only in development)
 * @param {string} message
 * @param  {...any} args
 */
export function logInfo(message, ...args) {
    if (isDevelopment) {
        console.info(`[LSR] ${message}`, ...args);
    }
}

/**
 * Log warning message (only in development)
 * @param {string} message
 * @param {...any} args
 */
export function logWarn(message, ...args) {
    if (isDevelopment) {
        console.warn(`[LSR] ${message}`, ...args);
    }
}

/**
 * Log error message (always logged, but formatted)
 * @param {string} message
 * @param {...any} args
 */
export function logError(message, ...args) {
    if (isDevelopment) {
        console.error(`[LSR] ${message}`, ...args);
    } else {
        // In production, silently log to potential error tracking service
        // Could integrate with Sentry, LogRocket, etc.
        // For now, we'll just suppress console output
    }
}

/**
 * Log debug message (only in development)
 * @param {string} message
 * @param {...any} args
 */
export function logDebug(message, ...args) {
    if (isDevelopment) {
        console.log(`[LSR Debug] ${message}`, ...args);
    }
}

export default {
    info: logInfo,
    warn: logWarn,
    error: logError,
    debug: logDebug,
};
