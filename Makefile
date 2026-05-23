.PHONY dev:
dev:
	@docker stop $$(docker ps -aq) || true
	@docker rm $$(docker ps -aq) || true
	@symfony server:stop --all
	@docker compose up -d
	@symfony server:start -d
