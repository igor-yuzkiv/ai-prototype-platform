import mitt from 'mitt'
export interface ServerEvent<TData = unknown> {
    event: string
    message: string
    data: TData
}

type ServerEventMap = Record<string, ServerEvent>
type Unsubscribe = () => void
type WildcardHandler = (payload: ServerEvent, eventName: string) => void

class ServerEventBus {
    private channel: ReturnType<typeof window.Echo.channel> | null = null
    private emitter = mitt<ServerEventMap>()
    private wildcardHandlers = new Map<string, Set<WildcardHandler>>()

    mount() {
        if (!window.Echo || !window.Pusher) {
            throw new Error(
                'ServerEventBus requires Laravel Echo and Pusher to be initialized. Please ensure the Echo plugin is properly set up.'
            )
        }

        if (this.channel) {
            console.warn('ServerEventBus is already initialized and listening to channel:', this.channel)
            return
        }

        this.channel = window.Echo.channel(import.meta.env.VITE_CLIENT_BROADCAST_CHANNEL)
        if (!this.channel) {
            throw new Error(
                `Failed to join channel: ${import.meta.env.VITE_CLIENT_BROADCAST_CHANNEL}. Please check your Echo configuration.`
            )
        }

        this.channel.listenToAll((event: string, payload: ServerEvent) => {
            this.handleServerEvent(payload.event, payload)
        })
    }

    unmount() {
        if (!this.channel) {
            console.warn('ServerEventBus is not initialized or already unmounted.')
            return
        }

        this.channel.stopListeningToAll()
        window.Echo.leaveChannel(import.meta.env.VITE_CLIENT_BROADCAST_CHANNEL)
        this.channel = null
        this.emitter.all.clear()
        this.wildcardHandlers.clear()
    }

    on<TData = unknown>(
        eventName: string,
        handler: (payload: ServerEvent<TData>, eventName: string) => void
    ): Unsubscribe {
        if (eventName.endsWith('*') && eventName.length > 1) {
            const prefix = eventName.slice(0, -1)
            let set = this.wildcardHandlers.get(prefix)
            if (!set) {
                set = new Set()
                this.wildcardHandlers.set(prefix, set)
            }
            set.add(handler as WildcardHandler)
            return () => this.off(eventName, handler)
        }

        this.emitter.on(eventName, handler as (payload: ServerEvent) => void)
        return () => this.off(eventName, handler)
    }

    off<TData = unknown>(eventName: string, handler: (payload: ServerEvent<TData>, eventName: string) => void): void {
        if (eventName.endsWith('*') && eventName.length > 1) {
            const prefix = eventName.slice(0, -1)
            this.wildcardHandlers.get(prefix)?.delete(handler as WildcardHandler)
            return
        }

        this.emitter.off(eventName, handler as (payload: ServerEvent) => void)
    }

    private handleServerEvent(eventName: string, payload: ServerEvent) {
        this.emitter.emit(payload.event, payload)

        for (const [prefix, handlers] of this.wildcardHandlers.entries()) {
            if (eventName.startsWith(prefix)) {
                handlers.forEach((h) => h(payload, eventName))
            }
        }
    }
}

const serverEventBus = new ServerEventBus()

export { serverEventBus }
