.PHONY dev:
dev:
	docker compose up -d
	symfony server:start -d
