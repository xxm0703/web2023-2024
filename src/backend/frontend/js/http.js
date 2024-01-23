import { SERVER_URL } from '../config.js';

export class Http {
  async post(path, config) {
    return this.request('POST', path, config);
  }

  async get(path, config = {}) {
    return this.request('GET', path, config);
  }

  async put(path, config) {
    return this.request('PUT', path, config);
  }

  async patch(path, config = {}) {
    return this.request('PATCH', path, config);
  }

  async delete(path, config = {}) {
    return this.request('DELETE', path, config);
  }

  async request(method, path, config) {
    const queryParams = new URLSearchParams(config.query).toString();

    const response = await fetch(
      `${SERVER_URL.replace(/\/$/, '')}/${path.replace(
        /^\//,
        ''
      )}${queryParams ? `?${queryParams}` : ''}`,
      {
        method,
        body: config.body ? JSON.stringify(config.body) : undefined,
        headers: {
          ...(config.body ? { 'Content-Type': 'application/json' } : {}),
        },
      }
    );

    // TODO: Better error handling
    if (!response.ok) {
      const responseText = await response.text();
      const error = this.safeParse(responseText);

      throw new Error('Something went wrong');
    }

    return response.json();
  }

  safeParse(data) {
    try {
      return JSON.parse(data);
    } catch {
      return data;
    }
  }
}
