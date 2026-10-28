import admin from '@/routes/admin';
import teacher from '@/routes/teacher';
import courses from '@/routes/courses';
import checkout from '@/routes/checkout';
import { home, dashboard } from '@/routes';

type RouteParams = Record<string, string | number | undefined>;

type RouteOptions = {
    query?: Record<string, string | number | undefined>;
    mergeQuery?: Record<string, string | number | undefined>;
};

const routes = {
    home,
    dashboard,
    admin,
    teacher,
    courses,
    checkout,
};

function isRouteFunction(value: unknown): value is (...args: any[]) => { url: string } {
    return typeof value === 'function';
}

function normalizeQuery(options?: RouteOptions) {
    if (!options?.query && !options?.mergeQuery) {
        return undefined;
    }

    const query = options?.query ?? options?.mergeQuery;
    return {
        query: Object.fromEntries(
            Object.entries(query ?? {}).map(([key, value]) => [key, String(value)]),
        ),
    };
}

function resolveRouteFunction(name: string) {
    const segments = name.split('.');
    let current: any = routes;

    for (const segment of segments) {
        if (current == null) {
            return null;
        }

        current = current[segment];
    }

    return isRouteFunction(current) ? current : null;
}

export function namedRoute(
    name: string,
    params: RouteParams = {},
    options?: RouteOptions,
): string {
    const routeFn = resolveRouteFunction(name);
    const queryOptions = normalizeQuery(options);

    if (routeFn) {
        try {
            const hasParams = Object.keys(params).length > 0;
            const routeDefinition = hasParams ? routeFn(params, queryOptions) : routeFn(queryOptions);
            return routeDefinition.url;
        } catch (error) {
            console.error(`Failed to resolve route '${name}'`, error);
        }
    }

    const queryString = queryOptions?.query
        ? `?${Object.entries(queryOptions.query)
              .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(String(value))}`)
              .join('&')}`
        : '';

    return `/${name.replace(/\./g, '/')}${queryString}`;
}
